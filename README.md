# 🏛️ SVLDK (Sistem Verifikasi & Layanan Data Kemiskinan)

SVLDK adalah sistem cerdas *Single Source of Truth* berbasis **Lingkungan Web Modern**, dikembangkan secara eksklusif untuk mengatasi kendala operasional pencatatan layanan kesejahteraan dan memvalidasi keabsahan status kemiskinan (SKTM) warga masyarakat secara dinamis dan *real-time*.

Dengan SVLDK, masyarakat tidak perlu lagi khawatir penolakan layanan akibat kelupaan membawa dokumen fisik, sementara instansi pemerintah mendapatkan kemudahan pelacakan serta tata kelola data (Data Governance) yang aman dan akuntabel.

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
SVLDK memiliki subsistem khusus terintegrasi dengan Data Warga:
- **Pencatatan Kemiskinan Interaktif (Toggle)**: Menggabungkan pengisian data *Parent* dan *Child* dengan mengalokasikan parameter (Rentang Pendapatan, Sumber Dokumen).
- **Masa Kedaluwarsa Dinamis**: Otomatis mengeksekusi perhitungan teknis sehingga status SKTM warga hanya berlaku tepat **3 bulan** dari waktu awal penetapan (Valid From).
- Status terstruktur berlapis: `ACTIVE` (Aktif Bantuan), `PENDING_REVIEW` (Perlu Tinjauan), dan `EXPIRED` (Telah Kedaluwarsa).

### 4. 🎨 Estetika & Keamanan Ekstra
Sistem dibangun tidak hanya dengan mengedepankan fungsional namun juga desain *"WOW Factor"*:
- **Aesthetic UI**: Arsitektur tampilan panel depan dengan struktur **Glassmorphism**, panel akrilik kabur (*Blur Backdrops*), efek partikel gradien bayangan eksklusif.
- **Dual-Theme Engine (Light/Dark Mode)**: Dikendalikan responsif melalui *localStorage* + `Alpine.js` agar mata Operator nyaman saat pendataan larut malam.
- **Custom Error Interfaces**: Modifikasi antarmuka *Error Page* bawaan menjadi halaman responsif *(404 Not Found, 403 Forbidden, 401 Unauthorized)* secara tematik agar memandu pengguna dengan aman saat tersesat.
- **Global SweetAlert2 Interception**: Semua sukses, error otorisasi, maupun validasi peringatan disadap dan diterjemahkan oleh antarmuka *SweetAlert* terapung lengkap dengan rekonsiliasi format pesan yang bersahabat (*User-Centric*).

---

## 🛠️ Stack Teknologi (Tech Stack)

Sistem ini dididik dengan pilar utama Framework PHP terdepan:
- **Mesin Inti**: PHP 8.4 + Laravel Framework 13.
- **Lapisan Penyajian (Frontend)**: Laravel Blade + Tailwind CSS v4 (Sistem Desain Utilitas Responsif).
- **Interaktivitas Visual**: Alpine.js (Untuk manuver antarmuka minimalis seperti modal, *Permission Syncing*, & sakelar *Light/Dark Mode*).
- **Basis Data**: MySQL 8.
- **Keamanan Input**: Form Requests terisolasi + Eksekusi `Nullable` dinamis pada parameter komponen SKTM tersembunyi.

---

## 📋 Status Pengembangan (Developer Log)

Penulisan kode dikendalikan dengan implementasi *"Do Things the Laravel 13 Way"*:
- ✅ **Fase 1**: Arsitektur Basis Data, Skema Tabel (Villages, Users, Permisson_Role, Citizens, PovertyRecords).
- ✅ **Fase 2**: Kontrak Otorisasi Otentikasi dan *Multi-Role Logic* tersemat melalui Seeder.
- ✅ **Fase 3**: Konfigurasi estetik *Super Dashboard* dengan Layout Universal.
- ✅ **Fase 4**: Penempatan Modul *Controller*, *Validation Rules* yang berlapis (menangani *bug valid_from.date*), *Middleware CheckPermission* di *app.php*.
- ✅ **Fase 5**: Modul Manajemen Administratif (User, Role, Desa, & Kecamatan), normalisasi database wilayah (Kecamatan > Desa), implementasi *Form Requests* terstruktur dalam folder `Web/`, dan restrukturisasi arsitektur folder untuk skalabilitas.
- ⏳ **Fase 6 (Sekarang)**: Sistem Integrasi Layanan Eksternal (API *Front Office*) & Notifikasi otomatis/Pekerja Layar Belakang (*Queue Worker SMS/WhatsApp Reminder*).

***Berkarya untuk Birokrasi yang Mulus. 🚀***
