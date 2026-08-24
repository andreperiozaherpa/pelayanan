# Firebase Realtime Database — Display Caller MPP

Sistem **Display Caller** memakai [Firebase Realtime Database](https://firebase.google.com/docs/database) sebagai media realtime antara backend Laravel (sumber kebenaran) dan aplikasi desktop Wails (layar TV/public display).

```
pelayanan (Laravel)          antrian (Wails + React)
 ┌──────────────────┐  write  ┌─────────────────────────┐
 │ QueueService ────┼────────→│ Firebase Realtime DB    │
 │ FirebaseService  │  ←──────┼───────── read (onValue) │
 └──────────────────┘  realtime└─────────────────────────┘
                                   display screen (browser)
```

## 1. Setup Backend Laravel (`pelayanan`)

1. Buat project di [Firebase Console](https://console.firebase.google.com) → **Add Project**.
2. **Project settings → Service accounts → Generate new private key** → simpan `service-account.json`.
3. **Realtime Database → Create database** (production mode), salin URL, contoh: `https://mpp-tubaba-default-rtdb.asia-southeast1.firebasedatabase.app`.
4. Isi `.env`:

```dotenv
FIREBASE_CREDENTIALS=/absolute/path/to/service-account.json
FIREBASE_DATABASE_URL=https://mpp-tubaba-default-rtdb.asia-southeast1.firebasedatabase.app
```

5. Dependensi (sudah terpasang): `kreait/firebase-php`.

> Selama kedua nilai di atas kosong, `FirebaseService::isReady()` mengembalikan `false`
> dan semua operasi Firebase menjadi no-op (aplikasi tetap berjalan normal).

## 2. Setup Frontend Wails (`antrian`)

Dependensi `firebase` (npm) sudah terpasang. Isi `.env` project (lihat `.env.example`):

```dotenv
VITE_FIREBASE_API_KEY=...
VITE_FIREBASE_AUTH_DOMAIN=...
VITE_FIREBASE_DATABASE_URL=https://mpp-tubaba-default-rtdb.asia-southeast1.firebasedatabase.app
VITE_FIREBASE_PROJECT_ID=...
VITE_FIREBASE_STORAGE_BUCKET=...
VITE_FIREBASE_MESSAGING_SENDER_ID=...
VITE_FIREBASE_APP_ID=...
```

Lalu jalankan `wails dev` / `wails build` agar variabel terbundle.

## 3. Rules (keamanan)

Pasang `database.rules.json` dari folder ini ke **Realtime Database → Rules**.
- `.read: true` — layar publik hanya perlu baca.
- `.write: auth != null` — tulis hanya dari backend Laravel (dengan Firebase Admin SDK, auth otomatis terisi).

> **Penting:** Firebase Realtime Database menolak tulis tanpa auth meskipun rules `.write: auth != null`
> karena permintaan `set()` dari Admin SDK tetap membawa credential. Sesuaikan rules bila perlu.

## 4. Struktur Data

Lihat contoh lengkap di [`db-structure.json`](./db-structure.json).

| Node | Bentuk | Ditulis oleh | Dibaca oleh |
|---|---|---|---|
| `current_call` | objek: `queue_number, gerai_name, agency, service_type, timestamp` | Laravel (setiap panggilan FO/Gerai) | layar display |
| `active_counters` | objek `{ <key>: { label, number, timestamp } }` | Laravel | layar display (5 kartu loket) |
| `recent_history` | array max 20: `{ queue_number, gerai_name, status, timestamp }` | Laravel | layar display (15 baris) |
| `display_settings` | objek header/TTS/warna (`header_title, header_subtitle, running_text, youtube_url, tts{enabled,rate,pitch}, colors{...}`) | Laravel (admin) | layar display + admin preview |

Key `active_counters`: `fo` (Front Office), `gerai-{id}` untuk setiap gerai, dan `simulasi` saat tombol **Simulasi Panggilan** ditekan dari halaman admin.

Status `recent_history`: `Selesai` (gerai selesai), `Tidak Hadir` (FO tolak / tidak hadir).

## 5. Alur Penulisan (backend `pelayanan`)

- Panggil FO (call) → `current_call` + counter `fo` + push history.
- FO reject / tidak hadir → history `Tidak Hadir` + hapus counter `fo`.
- Gerai panggil → `current_call` + counter `gerai-{id}` + push history `Selesai`.
- Gerai panggil ulang (recall) → `current_call` diupdate (nomor sama).
- Admin simpan pengaturan / test call → `display_settings` / `current_call` (+`simulasi` counter).

Semua penulisan realtime → layar display ikut berubah otomatis tanpa refresh.

## 6. Referensi Listener (frontend)

Semua listener sudah dibungkus di `src/services/firebase.js`:

```js
import { isFirebaseEnabled, subscribeDisplaySettings, subscribeCurrentCall,
         subscribeActiveCounters, subscribeRecentHistory } from '../services/firebase'

useEffect(() => {
  if (!isFirebaseEnabled()) return
  const offs = [
    subscribeDisplaySettings(setSettings),   // header, running text, warna, TTS
    subscribeCurrentCall(setCurrent),        // nomor + gerai besar
    subscribeActiveCounters(setCounters),    // 5 kartu loket
    subscribeRecentHistory(setHistory),      // 15 baris riwayat
  ]
  return () => offs.forEach(off => off?.())
}, [])
```

TTS diputar oleh browser (Web Speech API) dengan volume video YouTube diturunkan selama
pengumuman, lalu dikembalikan setelah selesai.