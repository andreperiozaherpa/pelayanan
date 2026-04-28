# 🏛️ Sistem Verifikasi Pelayanan Dokumen

Sistem Verifikasi Pelayanan Dokumen adalah sistem cerdas *Single Source of Truth* berbasis **Lingkungan Web Modern**, dikembangkan secara eksklusif untuk mengatasi kendala operasional pencatatan layanan kesejahteraan dan memvalidasi keabsahan status kemiskinan (SKTM) warga masyarakat secara dinamis dan *real-time*.

Dengan Sistem Verifikasi Pelayanan Dokumen, masyarakat tidak perlu lagi khawatir penolakan layanan akibat kelupaan membawa dokumen fisik, sementara instansi pemerintah mendapatkan kemudahan pelacakan serta tata kelola data (Data Governance) yang aman dan akuntabel.

---

## 🚀 Fitur Utama & Modul Fungsional (Feature Sets)

Aplikasi ini dibagi menjadi beberapa fungsionalitas inti yang didesain secara spesifik, terisolasi dengan ketat *(RBAC)*, dan dieksekusi dengan standar estetika tertinggi:

### 1. 🛡️ Role-Based Access Control (RBAC) & Data Isolation
Tiga poros otorisasi utama telah tertanam menggunakan *Custom Middleware Level Routing*:
- **Super Admin**: Menguasai akses eksekutif secara global ke seluruh entitas data warga di seluruh Desa.
- **Operator Desa**: Memiliki privilese untuk mengelola secara leluasa Data Master Warga, namun *diisolasi ketat* (Hanya dapat membaca dan memperbarui data warganya secara spesifik berdasarkan parameter wilayah).
- **Petugas Front Office**: Akses yang dikunci dengan aman. Fokus sekadar pada pemantauan sistem intelijen (*Verification & Services*) sehingga dicegat oleh *Error 403* untuk segala percobaan modifikasi Master Warga.

### 2. 🗂️ Manajemen Master Warga (Citizen Master Data)
Pusat ekosistem data demografi meliputi manajemen CRUD komprehensif:
- Enkripsi dan *Masking* penguasaan NIK warga (Misal: `320102********01`) demi mematuhi regulasi Pelindungan Data Pribadi (PDP).
- Form antarmuka interaktif yang merekam struktur lengkap warga dari NIK, Nama, Tanggal Lahir, hingga Kontak & Wilayah asal yang dinamis.
- **Data Master Wilayah Terstruktur**: Normalisasi data wilayah dari tingkat Kabupaten, Kecamatan, hingga Desa untuk akurasi pendataan dan kemudahan integrasi API di masa depan.

### 3. 📜 Mesin Penetapan Status Kemiskinan (SKTM Engine)
Sistem Verifikasi Pelayanan Dokumen memiliki subsistem khusus terintegrasi dengan Data Warga:
- **Pencatatan Kemiskinan Interaktif (Toggle)**: Menggabungkan pengisian data *Parent* dan *Child* dengan mengalokasikan parameter (Rentang Pendapatan, Sumber Dokumen).
- **Masa Kedaluwarsa Dinamis**: Otomatis mengeksekusi perhitungan teknis sehingga status SKTM warga hanya berlaku tepat **3 bulan** dari waktu awal penetapan (Valid From).
- Status terstruktur berlapis: `ACTIVE` (Aktif Bantuan), `PENDING_REVIEW` (Perlu Tinjauan), dan `EXPIRED` (Telah Kedaluwarsa).

### 4. 🎨 Estetika & Keamanan Ekstra
Sistem dibangun tidak hanya dengan mengedepankan fungsional namun juga desain *"WOW Factor"*:
- **Aesthetic UI**: Arsitektur tampilan panel depan dengan struktur **Glassmorphism**, panel akrilik kabur (*Blur Backdrops*), efek partikel gradien bayangan eksklusif, serta kelengkungan sudut yang elegan (*Restrained Roundedness*).
- **Dual-Theme Engine (Light/Dark Mode)**: Dikendalikan responsif melalui *localStorage* + `Alpine.js` agar mata Operator nyaman saat pendataan larut malam.
- **Custom Error Interfaces**: Modifikasi antarmuka *Error Page* bawaan menjadi halaman responsif *(404 Not Found, 403 Forbidden, 401 Unauthorized)* secara tematik agar memandu pengguna dengan aman saat tersesat.
- **Global SweetAlert2 Interception**: Semua sukses, error otorisasi, maupun validasi peringatan disadap dan diterjemahkan oleh antarmuka *SweetAlert* terapung lengkap dengan rekonsiliasi format pesan yang bersahabat (*User-Centric*).

### 5. 📄 Mesin Cetak Bukti Verifikasi (High-Fidelity PDF)
Integrasi dengan **Spatie Browsershot** untuk menghasilkan dokumen bukti verifikasi yang presisi:
- **Server-Side Rendering**: Menggunakan Headless Chrome untuk merender template Blade menjadi PDF berkualitas tinggi.
- **Dynamic QR Validation**: Setiap dokumen dilengkapi dengan URL validasi unik untuk memverifikasi keaslian dokumen secara *real-time*.
- **Environment-Aware Configuration**: Sistem secara otomatis mendeteksi jalur binari Chrome/Node baik di lingkungan lokal maupun kontainer Docker melalui konfigurasi `.env`.

### 6. 🏗️ Arsitektur Views Modular (Master-Data & Services)
Sistem Verifikasi Pelayanan Dokumen menerapkan standar pengorganisasian views yang ketat untuk memudahkan kolaborasi tim:
- **Modular Partials**: Komponen layout seperti *Header*, *Sidebar*, dan *Footer* dipisahkan ke dalam folder `layouts/partials/` untuk kemudahan pemeliharaan.
- **`master-data/`**: Folder khusus untuk seluruh modul pengelolaan data induk (CRUD) seperti Warga, Pengguna, Role, Kecamatan, dan Desa.
- **`services/`**: Folder khusus untuk fitur layanan aktif dan transaksional seperti Dashboard Statistik, Fitur Verifikasi NIK, dan Riwayat Audit Log.
- **`documents/`**: Penyimpanan template output dokumen legal (PDF) yang terpisah dari antarmuka interaktif.

### 6. 🚀 Optimasi Performa & UX
- **Zero-Flash Dark Mode**: Implementasi *blocking script* pada `<head>` untuk memastikan tema gelap dimuat secara instan tanpa glitch cahaya saat berpindah halaman.
- **Premium Search Standardization**: Antarmuka pencarian yang seragam dengan desain card premium, tombol filter dinamis, dan integrasi parameter query string yang presisi.

---

## 🛠️ Stack Teknologi (Tech Stack)

Sistem ini didukung oleh pilar teknologi modern:
- **Mesin Inti**: PHP 8.4 + Laravel Framework 13.
- **Lapisan Penyajian**: Laravel Blade + Tailwind CSS v4.
- **Interaktivitas**: Alpine.js (Theme switching, Sidebar maneuvers, Dynamic forms).
- **Audit & Logging**: Sistem Audit Log otomatis untuk setiap aksi Create, Update, dan Delete.
- **Form Validation**: Form Requests terpusat di `app/Http/Requests/Web/` untuk kebersihan kode.

---

## 📋 Status Pengembangan (Developer Log)

Penulisan kode dikendalikan dengan prinsip *"Do Things the Laravel Way"*:
- ✅ **Fase 1**: Arsitektur Basis Data, Skema Tabel, dan Relasi Normalisasi.
- ✅ **Fase 2**: Kontrak Otorisasi Otentikasi (RBAC) & Custom Middleware.
- ✅ **Fase 3**: Konfigurasi Aesthetic UI (Glassmorphism & Dual-Theme).
- ✅ **Fase 4**: Manajemen Master Warga & Engine SKTM (Business Logic).
- ✅ **Fase 5**: Modul Manajemen Administratif, Normalisasi Regional (Kecamatan > Desa), Restrukturisasi folder `Web/` (Requests/Resources), dan Reorganisasi Views Modular (`master-data` & `services`).
- ✅ **Fase 6**: Implementasi Mesin Cetak Bukti Verifikasi (Browsershot Integration), Restrukturisasi Layout Modular (Partials), dan Finalisasi Estetika Desain (Elegant Roundedness).
- ⏳ **Fase 7 (Next)**: Sistem Integrasi Layanan Eksternal & Notifikasi Otomatis.

***Berkarya untuk Birokrasi yang Cerdas. 🚀***
