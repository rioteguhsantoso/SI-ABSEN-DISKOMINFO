# SI-ABSEN DISKOMINFO

**Sistem Informasi Absensi Pegawai Berbasis Web dengan Pengenalan Wajah**

Sistem informasi absensi pegawai berbasis web yang dikembangkan untuk mendukung proses pencatatan dan pengelolaan kehadiran pegawai di **Dinas Komunikasi dan Informatika Kota Banjarmasin**.

Proyek ini dikembangkan sebagai bagian dari kegiatan Praktik Kerja Lapangan (PKL) dengan memanfaatkan teknologi web dan pengenalan wajah untuk mendukung proses absensi yang lebih terstruktur.

## Fitur Sistem

- **Autentikasi Pengguna** — Registrasi, verifikasi NIP, login, dan logout.
- **Dashboard Pegawai** — Menampilkan informasi dan aktivitas absensi pegawai.
- **Absensi Masuk dan Pulang** — Pencatatan kehadiran pegawai.
- **Pengenalan Wajah** — Integrasi Face API untuk mendukung proses identifikasi wajah.
- **Liveness Detection** — Pengembangan pemeriksaan keaktifan wajah melalui interaksi pengguna.
- **Validasi Lokasi** — Pemeriksaan lokasi absensi berdasarkan koordinat dan radius kantor.
- **Riwayat Absensi** — Menampilkan catatan kehadiran pegawai.
- **Rekapitulasi Absensi** — Pengolahan data kehadiran berdasarkan periode.
- **Pengelolaan Izin dan Cuti** — Pencatatan pengajuan izin dan cuti.
- **Perjalanan Dinas** — Pengelolaan data perjalanan dinas.
- **Laporan** — Penyediaan data laporan untuk kebutuhan administrasi.

*Catatan: Ketersediaan dan status implementasi setiap fitur dapat berbeda sesuai versi pengembangan sistem.*

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP | Bahasa pemrograman backend |
| Laravel | Framework aplikasi web |
| MySQL | Sistem manajemen basis data |
| HTML, CSS, JavaScript | Pengembangan antarmuka |
| Blade | Template engine Laravel |
| Face API.js | Deteksi dan pengenalan wajah |
| Vite | Pengelolaan aset frontend |
| XAMPP | Lingkungan pengembangan lokal |

## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat telah memiliki:

- PHP dengan versi yang sesuai dengan `composer.json`.
- Composer.
- MySQL atau MariaDB.
- Node.js dan npm.
- Web browser modern.

## Instalasi dan Konfigurasi

### 1. Clone Repository

```bash
git clone https://github.com/rioteghsantoso/SI-ABSEN-DISKOMINFO.git
```

Masuk ke direktori proyek:

```bash
cd SI-ABSEN-DISKOMINFO
```

### 2. Instalasi Dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Windows CMD:

```bash
copy .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

### 4. Konfigurasi Database

Buat database MySQL dengan nama:

```text
db_absensi_face
```

Sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_absensi_face
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password dengan konfigurasi MySQL lokal masing-masing.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Migrasi Database

```bash
php artisan migrate
```

Jalankan seeder hanya apabila tersedia dan sesuai dengan kebutuhan:

```bash
php artisan db:seed
```

### 7. Build Frontend

```bash
npm run build
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

## Face Recognition

Sistem menggunakan library **Face API.js** untuk mendukung proses deteksi dan pengenalan wajah melalui browser.

File library dan model pengenalan wajah tersedia pada direktori:

```text
public/
├── js/
│   └── face-api.min.js
└── models/
    ├── face_landmark_68/
    ├── face_recognition/
    └── ssd_mobilenetv1/
```

Pastikan file model tetap tersedia agar proses pemuatan model dapat dilakukan oleh aplikasi.

## Struktur Direktori

```text
SI-ABSEN-DISKOMINFO/
├── app/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── images/
│   ├── js/
│   └── models/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
└── README.md
```

## Keamanan dan Privasi

Repository ini merupakan dokumentasi dan source code pengembangan sistem.

- Jangan mengunggah file `.env`.
- Jangan mengunggah database produksi atau data pribadi pegawai.
- Jangan mempublikasikan foto pegawai maupun data biometrik wajah.
- Gunakan data fiktif untuk pengujian dan demonstrasi.
- Simpan kredensial dan konfigurasi sensitif di environment lokal atau server.

## Pengembang

**Rio Teguh Santoso**[ 	]+$
Program Studi S1 Sistem Informasi[ 	]+$
Fakultas Teknologi Informasi[ 	]+$
Universitas Islam Kalimantan Muhammad Arsyad Al-Banjari

## Lisensi

Proyek ini dikembangkan untuk keperluan Praktik Kerja Lapangan (PKL) dan pengembangan sistem informasi di lingkungan Dinas Komunikasi dan Informatika Kota Banjarmasin.