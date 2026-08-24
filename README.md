# 🏛️ Sistem Antrian & Pelayanan MPP Tubaba (SIBERUGO) — Backend

Backend **Single Source of Truth** untuk sistem antrian dan pelayanan publik di **Mal Pelayanan Publik (MPP) Kabupaten Tulang Bawang Barat**. Dikonsumsi oleh desktop client `antrian/` (Wails v2) melalui REST API, serta menyediakan **Web Admin** untuk pengelolaan data.

> Repo ini berisi 2 aplikasi: `antrian/` (Wails desktop client) dan `pelayanan/` (backend Laravel ini). Lihat `README.md` di root repo untuk gambaran keseluruhan.

---

## 🛠️ Tech Stack

| Layer    | Teknologi                                         |
| -------- | ------------------------------------------------- |
| Backend  | PHP ^8.3 (dev 8.4), Laravel Framework ^12.0       |
| Database | MySQL                                             |
| Auth API | Laravel Sanctum ^4.3 (token)                      |
| Realtime | Firebase Realtime Database (`kreait/firebase-php`) |
| PDF      | Spatie Browsershot ^5.2 + TCPDF/TTE               |
| TTE      | `lsnepomuceno/laravel-a1-pdf-sign` (OpenSSL PKCS#12) |
| QR Code  | `simplesoftwareio/simple-qrcode`                  |
| Frontend | Blade + Tailwind CSS v4 + Alpine.js + Vite        |
| Testing  | Pest ^4.6 + Laravel Boost                          |
| Formatter| Laravel Pint                                      |

Skala kode: **67 migration**, **50 model Eloquent**, ~30 controller web + 8 controller API v1.

---

## 🏗️ Arsitektur

```
┌──────────────────────────┐      HTTP REST + WebSocket      ┌──────────────────────────┐
│  antrian/  (Wails)       │ ──────────────────────────────→ │  pelayanan/  (Laravel)   │
│  Desktop client          │ ←────────────────────────────── │  API v1 (Queue, Auth,    │
│  (kiosk, FO, gerai,      │     JWT Bearer (Sanctum Token)  │   Services, SKM)         │
│   display)               │                                  │  Web Admin (CRUD MPP)    │
└──────────────────────────┘                                  │  Firebase Realtime       │
                                                             │  CMS Landing Page        │
                                                             └──────────────────────────┘
```

- **Provider**: semua data & logika bisnis berada di sini; `antrian/` hanya thin client.
- **API**: prefix `/api/v1`, response `{ "success": true, "data": {...} }` (error: `{ "success": false, "error": "..." }`).
- **Realtime**: broadcast event antrian ke Firebase Realtime Database (dibaca client via WebSocket ke Firebase).
- **Auth**: dua mode — web login (session) dan API token Sanctum untuk desktop client.

---

## 🧩 Modul Aplikasi

| Modul | Tabel Utama | Dipakai `antrian/`? |
| ----- | ----------- | ------------------- |
| 1. MPP Queue System | `mpp_queues`, `mpp_counters` | Ya (kiosk, FO, gerai, display) |
| 2. MPP Dynamic Services | `mpp_services`, `mpp_service_requests` | Ya (form di kiosk & pengajuan) |
| 3. Gerai / Anjungan | `mpp_gerais` | Ya (master gerai) |
| 4. Data Kependudukan | `citizens`, `household_cards`, `villages`, `districts` | Tidak |
| 5. SKTM & Surat Keterangan | `poverty_records`, `*_records` (domicile/move/arrival/death) | Tidak |
| 6. SKM / Survei Kepuasan | `skm_responses` | Ya (kiosk jawab survei) |
| 7. CMS Landing Page (SIBERUGO) | `cms_*` | Tidak |
| 8. GIS / Peta Interaktif | `map_*` | Tidak |
| 9. RBAC | `roles`, `permissions`, `users` | Ya (login & role check) |

> **Perbedaan kunci:** Modul 1 mengelola **tiket antrian** (perjalanan waiting → done), Modul 2 mengelola **form & isi permohonan layanan** (`submitted_form_data`). Keduanya **berbagi urutan nomor antrian** pada layanan yang sama.

---

## 🔄 Proses Bisnis

### Hirarki Data MPP

```
instansi (opds)
   └── gerai (mpp_gerais)         → many
         └── loket (mpp_counters) → many  (via gerai_id)
```

- `mpp_counters` terhubung langsung ke gerai via **`gerai_id`** (bukan `opd_id`).
- `mpp_services` dan SKM/survei **tetap** berelasi ke instansi (**`opd_id`**).
- Model: `Opd → gerais()`, `Gerai → counters()`, `Counter → gerai()`.

### Alur Tiket Antrian

```
waiting_fo → calling_fo → waiting_gerai → calling_gerai → done
                ↓                              ↑
            rejected                       (FO skip → waiting_fo)
```

| Status          | Arti                                   |
| --------------- | -------------------------------------- |
| `waiting_fo`    | Menunggu dipanggil FO                  |
| `calling_fo`    | Sedang dipanggil/diproses FO           |
| `waiting_gerai` | Di-forward ke gerai, menunggu dipanggil |
| `calling_gerai` | Sedang dilayani di gerai               |
| `done`          | Selesai                                |
| `rejected`      | Ditolak FO (dengan alasan)             |

Operasi per pelaku (controller `MppQueueOperationController`):
- **FO**: `foWaiting`, `foCalling`, `foCall`, `foForward`, `foReject`, `foRecall`, `foSkip`.
- **Gerai**: `geraiWaiting`, `geraiCalling`, `geraiCall`, `geraiComplete`.

### Alur Pengajuan Layanan

Dua jalur submit yang **berbagi nomor antrian** pada layanan yang sama:
- **Web / Admin**: `POST /mpp/{mppService:slug}/simpan` (petugas mengisi untuk warga).
- **Kiosk (Anjungan)**: `POST /api/v1/services/{service}/requests` (warga isi di mesin antrian).

Form dirender dinamis dari `fields` layanan; nilai terkirim disimpan di `submitted_form_data` sebagai `{ label, type, value }`.

---

## 🔌 REST API v1

Semua endpoint di bawah prefix `/api/v1/`.

### Auth

| Method | Endpoint        | Auth    | Deskripsi                                     |
| ------ | --------------- | ------- | --------------------------------------------- |
| POST   | `/auth/login`   | No      | Login (email/username + password + role filter) |
| POST   | `/auth/refresh` | No      | Refresh token                                 |
| POST   | `/auth/logout`  | Sanctum | Logout                                        |

### Public (Kiosk)

| Method | Endpoint                          | Deskripsi                                |
| ------ | --------------------------------- | ---------------------------------------- |
| GET    | `/services`                       | Daftar layanan (dikelompokkan per instansi) |
| GET    | `/services/{service}`             | Detail layanan + fields builder          |
| POST   | `/services/{service}/requests`    | Buat pengajuan dari kiosk (bagian dari alur tiket) |
| POST   | `/tickets`                        | Buat tiket antrian                       |
| GET    | `/opd/{opd}/skm`                  | Ambil daftar pertanyaan SKM per OPD      |
| POST   | `/opd/{opd}/skm`                  | Simpan jawaban SKM                       |

### Autentikasi (Sanctum — dipakai `antrian/`)

| Method | Endpoint                     | Deskripsi                              |
| ------ | ---------------------------- | -------------------------------------- |
| GET    | `/gerai`                     | Daftar gerai untuk petugas staff       |
| GET    | `/fo/waiting`, `/fo/calling` | Antrian FO                             |
| POST   | `/fo/call` `/fo/forward` `/fo/reject` `/fo/recall` `/fo/skip` | Operasi FO |
| GET    | `/gerai/{gerai}/waiting`, `/gerai/{gerai}/calling` | Antrian gerai |
| POST   | `/gerai/call` `/gerai/complete` | Operasi gerai                        |
| CRUD   | `/citizens`                  | Manajemen warga                        |
| GET    | `/poverty/status/{nik}`      | Cek status kemiskinan (cache 24 jam)   |
| POST   | `/poverty`                   | Simpan data kemiskinan                 |

---

## 🖥️ Web Admin

Route berada di bawah prefix `/mpp` (MPP) dan `/` (master data), dilindungi permission masing-masing.

### MPP (`/mpp`)

| Prefix            | Fungsi                                           | Permission           |
| ----------------- | ------------------------------------------------ | -------------------- |
| `/pengajuan`      | Daftar/detail pengajuan, form buat & simpan      | `mpp.pengajuan.*`    |
| `/pelayanan`      | CRUD layanan + form builder + upload logo        | `mpp.service.manage` |
| `/daftar-gerai`   | CRUD master gerai + upload logo                  | `mpp.gerai.manage`   |
| `/daftar-loket`   | CRUD loket/counter (`orderBy code` numerik)      | `mpp.counter.manage` |
| `/pengaturan-loket` | Penugasan petugas ke loket (1 loket = 1 user, tukar = swap) | `mpp.counter.manage` |
| `/survei`         | Kelola & ekspor SKM                              | `mpp.skm.manage`     |

### Master Data & Lainnya

| Area                        | Controller / Keterangan                    |
| --------------------------- | ------------------------------------------ |
| Dashboard admin & desa      | `DashboardController`                      |
| CRUD warga, users, roles, villages, districts, opds | `CitizenController`, `UserController`, `RoleController`, `VillageController`, `DistrictController`, `OpdController` |
| Layanan & Verifikasi (`/layanan`) | `VerificationController`, `ServiceController`, `HistoryController` |
| CMS (SIBERUGO)              | `Cms*Controller` (articles, pages, menus, banners, faqs, testimonials, teams, settings, media, complaints) |
| GIS / Peta                  | `Map*Controller` (regions, zones, locations, zone-types, location-categories) |
| Publik (landing)            | `LandingPageController` + `api/map/*` (regions, zones, locations) |
| Surat & Verifikasi publik   | `CertificateController`, `PublicVerificationController`, `/proof/{nik}/{type}` |
| Laporan                     | `ReportController` (export citizens & audit log) |
| Upload generik              | `UploadController` (`POST /mpp/upload`)    |

### Public Routes

- `/` → landing page SIBERUGO
- `/siberugo` → halaman & peta interaktif
- `/kontak/pengaduan` → submit pengaduan publik
- `/{section}/{slug}` → halaman statis landing page (route dinamis di bawah, hindari konflik)
- `/cek-surat`, `/proof/{nik}/{type}` → verifikasi dokumen publik via QR

---

## 📂 Direktori Penting

```
pelayanan/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/          # REST API (Auth, Queue, Service, SKM, Citizen, Poverty)
│   │   │   └── Web/             # Web admin & public controllers
│   │   ├── Middleware/          # CheckRole, CheckPermission
│   │   └── Requests/            # Form request validation
│   ├── Enums/                   # Enum status (tiket, pengajuan, poverty)
│   ├── Models/                  # 50 model Eloquent
│   ├── Observers/               # LandingPageCacheObserver
│   ├── Repositories/            # Repository layer
│   ├── Services/                # QueueService, FirebaseService, CertificateService,
│   │   │                        # SkmService, Tte, AuditService, Cms*
│   └── Traits/                  # Auditable, HasSlug
├── routes/
│   ├── web.php                  # Web admin + public routes
│   └── api.php                  # API v1 (public + sanctum)
├── database/
│   ├── migrations/              # 67 file
│   └── seeders/                 # Mpp*Seeder, RBAC, Opd, CMS, Map
├── resources/views/
│   ├── services/admin/mpp/      # MPP (pengajuan, pelayanan, daftar-gerai, daftar-loket, pengaturan-loket, survei)
│   ├── cms/                     # View CMS (termasuk partials/dropzone-upload.blade.php)
│   ├── documents/               # Template dokumen surat (PDF)
│   └── layouts/                 # Layout Blade (app, public, sidebar)
└── config/                      # Konfigurasi Laravel
```

---

## 🌱 Pengembangan (Lerd)

Proyek berjalan di **lerd** (Podman). Lihat blok `lerd` di `AGENTS.md` untuk detail tool MCP.

```bash
lerd site list        # Discover site
lerd env setup        # Atur .env (pastikan db sudah di-set dulu)
lerd framework setup  # Wajib setelah env setup: migrate + storage:link
lerd exec vendor_run  # Jalankan tooling proyek (pest, pint, dll)
```

Perintah penting:

| Perintah                                | Fungsi                          |
| --------------------------------------- | ------------------------------- |
| `php artisan test --compact`            | Jalankan seluruh test (Pest)    |
| `vendor/bin/pint --format agent`        | Format kode PHP                 |
| `npm run build` / `npm run dev`         | Build / develop frontend Vite   |
| `php artisan route:list --path=mpp`     | Lihat rute MPP                  |
| `php artisan config:show app.name`      | Baca konfigurasi                |

---

## 📋 Konvensi Kode (ringkas)

- **Format nomor antrian**: `{kode_gerai}-{nomor_urut}` (contoh gerai `GR-001` → `GR-001`, `GR-002`); reset harian; kiosk & web **berbagi urutan**.
- **Seeder MPP** wajib prefix `mpp` (`MppGeraiSeeder`, `MppCounterSeeder`, `MppServiceSeeder`); `OpdSeeder` tidak.
- **Permission MPP** ber-prefix `mpp.` (`mpp.queue.operate`, `mpp.service.manage`, `mpp.gerai.manage`, `mpp.counter.manage`, `mpp.skm.manage`, `mpp.pengajuan.*`).
- **Kode loket** = angka urut tanpa nol di depan (`1`..`24`), urut numerik via `CAST(code AS UNSIGNED)`.
- **1 loket = 1 user**; perubahan penugasan hanya via **Tukar Loket** (swap).
- **Upload logo** via Dropzone (`cms/partials/dropzone-upload.blade.php`), response `{ success: true, path, url }`.
- **Response API**: `{ success: true, data }` / `{ success: false, error }`.
- **Database**: JANGAN `db:wipe` / `migrate:fresh` tanpa izin eksplisit; waspadai `--env=testing` fallback ke `.env` utama bila `.env.testing` tidak ada.

---

*Berkarya untuk Birokrasi yang Cerdas. 🚀*
