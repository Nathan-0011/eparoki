# 🖥️ BACKEND — Dokumentasi Teknis Laravel

> **eKatolik — Paroki Santo Fidelis Parapat**
> Backend: Laravel 11 · PHP 8.2+ · MySQL 8.0

---

## 📋 Daftar Isi

1. [Gambaran Arsitektur](#-gambaran-arsitektur)
2. [Penjelasan Modul Laravel](#-penjelasan-modul-laravel)
3. [Daftar API Endpoint](#-daftar-api-endpoint)
4. [Format Response API](#-format-response-api)
5. [Setup Environment Lokal](#-setup-environment-lokal)
6. [Migrasi & Seeder](#-migrasi--seeder)
7. [Menjalankan Scheduler](#-menjalankan-scheduler)
8. [Struktur Folder Laravel](#-struktur-folder-laravel)

---

## 🏗️ Gambaran Arsitektur

Backend eKatolik dibangun menggunakan **Laravel 11** dengan pola arsitektur **MVC (Model-View-Controller)** yang diperluas dengan lapisan **Service** untuk pemisahan business logic. Sistem ini melayani dua jenis klien secara bersamaan:

```
┌─────────────────────────────────────────────────────────┐
│                    eparoki-platform                     │
│                   (Laravel 11 Backend)                  │
│                                                         │
│  ┌──────────────┐        ┌──────────────────────────┐  │
│  │  Web Admin   │        │      REST API Layer       │  │
│  │  (Blade +    │        │   /api/v1/...             │  │
│  │   Sanctum    │        │   (Sanctum Token Auth)    │  │
│  │   Session)   │        └──────────┬───────────────┘  │
│  └──────┬───────┘                   │                   │
│         │                           │                   │
│  ┌──────▼───────────────────────────▼───────────────┐  │
│  │              Controllers Layer                    │  │
│  │  Api/*Controller   |   Web/*Controller           │  │
│  └──────────────────────────┬────────────────────── ┘  │
│                             │                           │
│  ┌──────────────────────────▼────────────────────────┐  │
│  │              Services Layer (Business Logic)       │  │
│  └──────────────────────────┬────────────────────── ─┘  │
│                             │                           │
│  ┌──────────────────────────▼────────────────────────┐  │
│  │           Models Layer (Eloquent ORM)              │  │
│  └──────────────────────────┬─────────────────────── ┘  │
│                             │                           │
│  ┌──────────────────────────▼────────────────────────┐  │
│  │              MySQL Database                        │  │
│  └────────────────────────────────────────────────── ┘  │
│                                                         │
│  ┌──────────────────────────────────────────────────┐   │
│  │    Laravel Scheduler (Cron)                       │   │
│  │    → Reset Intensi Misa setiap Senin 00:00 WIB   │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
        ▲                          ▲
        │                          │
┌───────┴──────┐         ┌────────┴────────┐
│  Web Browser │         │  Flutter Mobile │
│ (Admin Panel)│         │   (Umat/Petugas)│
└──────────────┘         └─────────────────┘
```

### Prinsip Desain

| Prinsip | Implementasi |
|---|---|
| **Separation of Concerns** | Controller → Service → Model (3 lapisan) |
| **API-First** | Semua fitur mobile diakses via REST API `/api/v1/` |
| **Stateless Auth** | Laravel Sanctum token untuk mobile |
| **Stateful Auth** | Session Laravel untuk web admin |
| **Scheduled Jobs** | Laravel Scheduler untuk auto-reset intensi |
| **File Storage** | Laravel Storage + symbolic link (`storage/app/public`) |

---

## 📦 Penjelasan Modul Laravel

### 1. 🔐 Modul Autentikasi (`AuthController`)

Menangani dua jenis autentikasi:

- **Admin** — Login via email & password. Menggunakan Laravel Sanctum untuk menerbitkan API token yang dipakai oleh web admin panel.
- **Umat** — Login via Google OAuth menggunakan **Laravel Socialite**. Umat dapat mengakses konten tanpa login (mode publik), namun Google Sign-In tersedia sebagai fitur opsional untuk personalisasi.

**Alur Google OAuth:**
```
Mobile → GET /auth/google/redirect
       → Redirect ke Google Consent Screen
       → Callback: GET /auth/google/callback
       → Simpan/update umat_users
       → Return API token (Sanctum)
```

---

### 2. 📅 Modul Kalender Liturgi (`LiturgicalCalendarController`)

Mengelola tema dan keterangan liturgi per periode (tahunan, bulanan, mingguan). Admin mengisi data via web admin panel, dan mobile mengambilnya via API. Data ini menjadi "induk" bagi jadwal ibadah yang terhubung ke minggu tertentu.

---

### 3. 🕊️ Modul Jadwal Ibadah (`JadwalIbadahController`)

CRUD jadwal ibadah harian dan mingguan. Setiap jadwal dapat dikaitkan dengan entri kalender liturgi tertentu. Endpoint API mengembalikan jadwal berdasarkan filter minggu yang diminta mobile.

---

### 4. 👥 Modul Data Umat (`LingkunganController`, `KepalaKeluargaController`)

Mengelola struktur hierarki umat:
```
Paroki Santo Fidelis
  └── Lingkungan (±10 lingkungan)
        └── Kepala Keluarga (±30 KK per lingkungan)
```
Admin melakukan input via web, umat hanya bisa membaca via mobile (read-only).

---

### 5. 👨‍⚕️ Modul Pastor & BPH (`PastorController`, `BphController`)

- **Pastor**: Menyimpan riwayat pastor paroki dari periode ke periode, lengkap dengan foto dan biografi.
- **BPH**: Menyimpan struktur Badan Pengurus Harian aktif beserta jabatan, foto, dan periode kepengurusan.

---

### 6. 🖼️ Modul Banner (`BannerController`)

Upload dan manajemen gambar banner kegiatan (Paskah, Natal, Novena, dll.). Setiap banner memiliki `start_date` dan `end_date`. API secara otomatis hanya mengembalikan banner yang masih aktif (tanggal saat ini berada di antara `start_date` dan `end_date`).

---

### 7. 🙏 Modul Intensi Misa (`IntensiMisaController`)

Admin menginput nama keluarga, nominal, dan keterangan intensi. Mobile hanya menampilkan intensi untuk **minggu berjalan**. Data lama tidak dihapus permanen — melainkan diarsipkan (soft archive via kolom `week_of`). Reset otomatis dijalankan melalui **Laravel Scheduler** setiap Senin pukul 00:00 WIB.

---

### 8. 🎵 Modul Nomor Lagu (`SongNumberController`)

Menerima input nomor lagu 4 digit dari aplikasi mobile. Backend meneruskan nomor tersebut ke perangkat IoT (ESP32) melalui:
- **HTTP REST**: Jika ESP32 berjalan dalam mode web server
- **MQTT Publish**: Jika menggunakan broker MQTT (Mosquitto/HiveMQ)

Nomor lagu terbaru juga disimpan di database untuk keperluan logging.

---

## 🌐 Daftar API Endpoint

> **Base URL:** `http://localhost:8000/api/v1`
> **Auth:** Bearer Token (Sanctum) — wajib untuk endpoint yang ditandai 🔒

---

### 🔐 Autentikasi

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `POST` | `/auth/login` | Login admin (email + password), return token | ❌ |
| `POST` | `/auth/logout` | Logout, revoke token | 🔒 |
| `GET` | `/auth/google/redirect` | Redirect ke Google OAuth | ❌ |
| `GET` | `/auth/google/callback` | Callback Google OAuth, return token | ❌ |
| `GET` | `/auth/me` | Ambil data user yang sedang login | 🔒 |

---

### 📅 Kalender Liturgi

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `GET` | `/liturgical-calendars` | Ambil semua kalender liturgi | ❌ |
| `GET` | `/liturgical-calendars/{id}` | Detail satu entri kalender | ❌ |
| `GET` | `/liturgical-calendars?year={year}&week={week}` | Filter per tahun & minggu | ❌ |
| `POST` | `/liturgical-calendars` | Tambah entri kalender (admin) | 🔒 |
| `PUT` | `/liturgical-calendars/{id}` | Update entri kalender (admin) | 🔒 |
| `DELETE` | `/liturgical-calendars/{id}` | Hapus entri kalender (admin) | 🔒 |

---

### 🕊️ Jadwal Ibadah

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `GET` | `/jadwal-ibadah` | Ambil semua jadwal ibadah | ❌ |
| `GET` | `/jadwal-ibadah?week_of={date}` | Filter jadwal per minggu (YYYY-MM-DD) | ❌ |
| `GET` | `/jadwal-ibadah/{id}` | Detail satu jadwal | ❌ |
| `POST` | `/jadwal-ibadah` | Tambah jadwal (admin) | 🔒 |
| `PUT` | `/jadwal-ibadah/{id}` | Update jadwal (admin) | 🔒 |
| `DELETE` | `/jadwal-ibadah/{id}` | Hapus jadwal (admin) | 🔒 |

---

### 👥 Lingkungan & Kepala Keluarga

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `GET` | `/lingkungan` | Daftar semua lingkungan | ❌ |
| `GET` | `/lingkungan/{id}` | Detail lingkungan + daftar KK | ❌ |
| `POST` | `/lingkungan` | Tambah lingkungan (admin) | 🔒 |
| `PUT` | `/lingkungan/{id}` | Update lingkungan (admin) | 🔒 |
| `DELETE` | `/lingkungan/{id}` | Hapus lingkungan (admin) | 🔒 |
| `GET` | `/kepala-keluarga` | Daftar semua kepala keluarga | ❌ |
| `GET` | `/kepala-keluarga?lingkungan_id={id}` | Filter KK per lingkungan | ❌ |
| `GET` | `/kepala-keluarga/{id}` | Detail satu kepala keluarga | ❌ |
| `POST` | `/kepala-keluarga` | Tambah KK (admin) | 🔒 |
| `PUT` | `/kepala-keluarga/{id}` | Update KK (admin) | 🔒 |
| `DELETE` | `/kepala-keluarga/{id}` | Hapus KK (admin) | 🔒 |

---

### 👨‍⚕️ Pastor & BPH

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `GET` | `/pastors` | Daftar semua pastor (riwayat) | ❌ |
| `GET` | `/pastors/active` | Pastor aktif saat ini | ❌ |
| `GET` | `/pastors/{id}` | Detail profil pastor | ❌ |
| `POST` | `/pastors` | Tambah data pastor (admin) | 🔒 |
| `PUT` | `/pastors/{id}` | Update data pastor (admin) | 🔒 |
| `DELETE` | `/pastors/{id}` | Hapus data pastor (admin) | 🔒 |
| `GET` | `/bph` | Daftar semua anggota BPH | ❌ |
| `GET` | `/bph/active` | Struktur BPH periode aktif | ❌ |
| `POST` | `/bph` | Tambah anggota BPH (admin) | 🔒 |
| `PUT` | `/bph/{id}` | Update anggota BPH (admin) | 🔒 |
| `DELETE` | `/bph/{id}` | Hapus anggota BPH (admin) | 🔒 |

---

### 🖼️ Banner Kegiatan

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `GET` | `/banners` | Daftar banner aktif saat ini | ❌ |
| `GET` | `/banners/all` | Semua banner (termasuk expired) | 🔒 |
| `POST` | `/banners` | Upload banner baru (admin) | 🔒 |
| `PUT` | `/banners/{id}` | Update banner (admin) | 🔒 |
| `DELETE` | `/banners/{id}` | Hapus banner (admin) | 🔒 |

---

### 🙏 Intensi Misa

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `GET` | `/intensi-misa` | Daftar intensi minggu berjalan | ❌ |
| `GET` | `/intensi-misa/archive` | Arsip intensi minggu lalu | 🔒 |
| `POST` | `/intensi-misa` | Tambah intensi baru (admin) | 🔒 |
| `PUT` | `/intensi-misa/{id}` | Update intensi (admin) | 🔒 |
| `DELETE` | `/intensi-misa/{id}` | Hapus intensi (admin) | 🔒 |

---

### 🎵 Nomor Lagu (IoT)

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| `GET` | `/song-numbers/latest` | Ambil nomor lagu terakhir dikirim | ❌ |
| `POST` | `/song-numbers` | Kirim nomor lagu baru ke IoT | 🔒 |
| `GET` | `/song-numbers/history` | Riwayat nomor lagu | 🔒 |

---

## 📨 Format Response API

### ✅ Response Sukses

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {
    // payload data
  },
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 72
  }
}
```

### ✅ Response Sukses (Single Resource)

```json
{
  "success": true,
  "message": "Data pastor berhasil diambil",
  "data": {
    "id": 1,
    "nama": "Rm. Yohanes Situmorang",
    "periode_mulai": "2022-01-01",
    "periode_selesai": null,
    "foto_url": "http://localhost:8000/storage/pastors/foto.jpg",
    "biografi": "...",
    "is_active": true
  }
}
```

### ❌ Response Error — Validasi (422)

```json
{
  "success": false,
  "message": "Data tidak valid",
  "errors": {
    "nama": ["Nama wajib diisi"],
    "nominal": ["Nominal harus berupa angka"]
  }
}
```

### ❌ Response Error — Tidak Terautentikasi (401)

```json
{
  "success": false,
  "message": "Unauthenticated. Token tidak valid atau sudah kadaluarsa.",
  "data": null
}
```

### ❌ Response Error — Tidak Ditemukan (404)

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null
}
```

### ❌ Response Error — Server (500)

```json
{
  "success": false,
  "message": "Terjadi kesalahan pada server. Silakan coba lagi.",
  "data": null
}
```

---

## ⚙️ Setup Environment Lokal

### Langkah 1 — Persiapan

```bash
# Pastikan XAMPP berjalan (Apache + MySQL)
# Buka panel XAMPP dan START Apache & MySQL

# Masuk ke folder backend
cd eparoki-platform
```

### Langkah 2 — Instalasi Dependensi

```bash
composer install
npm install
```

### Langkah 3 — Konfigurasi Environment

```bash
# Salin template .env
cp .env.example .env

# Generate App Key
php artisan key:generate
```

Edit file `.env`:

```env
APP_NAME="eKatolik"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekatoli_db
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=sync

MAIL_MAILER=log

GOOGLE_CLIENT_ID=xxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

MQTT_HOST=127.0.0.1
MQTT_PORT=1883
MQTT_TOPIC_SONG=ekatoli/song_number
```

### Langkah 4 — Buat Database

```sql
-- Buka phpMyAdmin atau terminal MySQL
CREATE DATABASE ekatoli_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Langkah 5 — Jalankan Server

```bash
php artisan serve
# Server berjalan di: http://localhost:8000
```

---

## 🗄️ Migrasi & Seeder

### Menjalankan Migrasi

```bash
# Jalankan semua migrasi (buat semua tabel)
php artisan migrate

# Rollback & ulang dari awal (HATI-HATI: menghapus semua data)
php artisan migrate:fresh

# Rollback migrasi terakhir
php artisan migrate:rollback
```

### Urutan Migrasi

> Migrasi berjalan secara berurutan berdasarkan timestamp file:

```
2024_01_01_000001_create_users_table.php
2024_01_01_000002_create_lingkungan_table.php
2024_01_01_000003_create_kepala_keluarga_table.php
2024_01_01_000004_create_liturgical_calendars_table.php
2024_01_01_000005_create_jadwal_ibadah_table.php
2024_01_01_000006_create_pastors_table.php
2024_01_01_000007_create_bph_members_table.php
2024_01_01_000008_create_banners_table.php
2024_01_01_000009_create_intensi_misa_table.php
2024_01_01_000010_create_song_numbers_table.php
2024_01_01_000011_create_umat_users_table.php
```

### Menjalankan Seeder

```bash
# Jalankan semua seeder
php artisan db:seed

# Jalankan seeder tertentu
php artisan db:seed --class=LingkunganSeeder
php artisan db:seed --class=AdminUserSeeder

# Fresh migration + seed (development)
php artisan migrate:fresh --seed
```

### Daftar Seeder

| Seeder | Deskripsi |
|---|---|
| `AdminUserSeeder` | Buat akun admin default |
| `LingkunganSeeder` | 10 data lingkungan paroki |
| `PastorSeeder` | Data pastor (dummy) |
| `BphSeeder` | Struktur BPH aktif (dummy) |
| `BannerSeeder` | Banner contoh |
| `LiturgicalCalendarSeeder` | Tema kalender liturgi contoh |

---

## ⏰ Menjalankan Scheduler

Laravel Scheduler digunakan untuk **auto-reset intensi misa setiap Senin pukul 00:00 WIB**.

### Mode Development (manual, tanpa cron)

```bash
# Jalankan scheduler secara manual (polling tiap 60 detik)
php artisan schedule:work
```

### Mode Production (cron job server)

Tambahkan entry berikut ke **crontab** server:

```bash
# Buka crontab
crontab -e

# Tambahkan baris berikut (jalankan artisan schedule:run setiap menit)
* * * * * cd /path/to/eparoki-platform && php artisan schedule:run >> /dev/null 2>&1
```

### Konfigurasi Jadwal (Kernel / routes/console.php)

```php
// routes/console.php (Laravel 11)
use Illuminate\Support\Facades\Schedule;

Schedule::command('intensi:reset')
    ->weeklyOn(1, '00:00')  // Senin, jam 00:00
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();
```

### Artisan Command Reset Intensi

```php
// app/Console/Commands/ResetIntensiMisa.php

public function handle(): void
{
    $weekOf = now()->startOfWeek()->format('Y-m-d');
    
    // Arsipkan intensi minggu lalu dengan menandai week_of
    // Data tidak dihapus, hanya tidak muncul di query minggu berjalan
    $this->info("Intensi misa direset untuk minggu: {$weekOf}");
}
```

### Uji Scheduler Secara Manual

```bash
# Jalankan command reset intensi langsung
php artisan intensi:reset

# List semua jadwal yang terdaftar
php artisan schedule:list
```

---

## 📂 Struktur Folder Laravel

```
eparoki-platform/
│
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── ResetIntensiMisa.php       # Command reset intensi misa
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                       # Controller untuk mobile API
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── BannerController.php
│   │   │   │   ├── BphController.php
│   │   │   │   ├── IntensiMisaController.php
│   │   │   │   ├── JadwalIbadahController.php
│   │   │   │   ├── KepalaKeluargaController.php
│   │   │   │   ├── LingkunganController.php
│   │   │   │   ├── LiturgicalCalendarController.php
│   │   │   │   ├── PastorController.php
│   │   │   │   └── SongNumberController.php
│   │   │   └── Web/                       # Controller untuk admin panel
│   │   │       └── DashboardController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   └── AdminOnly.php              # Middleware khusus admin
│   │   │
│   │   └── Requests/                      # Form Request Validation
│   │       ├── StoreIntensiMisaRequest.php
│   │       ├── StoreSongNumberRequest.php
│   │       └── ...
│   │
│   ├── Models/
│   │   ├── User.php                       # Admin user
│   │   ├── UmatUser.php                   # Umat (Google OAuth)
│   │   ├── Lingkungan.php
│   │   ├── KepalaKeluarga.php
│   │   ├── LiturgicalCalendar.php
│   │   ├── JadwalIbadah.php
│   │   ├── Pastor.php
│   │   ├── BphMember.php
│   │   ├── Banner.php
│   │   ├── IntensiMisa.php
│   │   └── SongNumber.php
│   │
│   └── Services/                          # Business logic layer
│       ├── IntensiMisaService.php
│       ├── SongNumberService.php          # Publish ke MQTT / HTTP IoT
│       └── BannerService.php             # Filter banner aktif
│
├── bootstrap/
│   ├── app.php                            # App bootstrap Laravel 11
│   └── providers.php
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── mqtt.php                           # Konfigurasi MQTT broker
│   └── sanctum.php
│
├── database/
│   ├── migrations/                        # Semua file migrasi
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── AdminUserSeeder.php
│       ├── LingkunganSeeder.php
│       └── ...
│
├── public/
│   └── index.php                          # Entry point web
│
├── resources/
│   ├── views/
│   │   ├── layouts/                       # Template Blade utama
│   │   ├── auth/                          # Halaman login admin
│   │   └── admin/                         # Panel admin per modul
│   └── js/ & css/
│
├── routes/
│   ├── api.php                            # Semua route API (/api/v1/...)
│   ├── web.php                            # Route web admin
│   └── console.php                        # Scheduler & artisan commands
│
├── storage/
│   └── app/
│       └── public/                        # File upload publik
│           ├── pastors/                   # Foto pastor
│           ├── bph/                       # Foto BPH
│           └── banners/                   # Gambar banner
│
├── tests/
│   ├── Feature/                           # Integration tests
│   └── Unit/                              # Unit tests
│
├── .env.example
├── artisan
├── composer.json
└── vite.config.js
```

---

> 📄 Lihat juga: [DATABASE.md](DATABASE.md) · [MOBILE.md](MOBILE.md) · [IOT.md](IOT.md)
>
> ✝️ **eKatolik** — Paroki Santo Fidelis Parapat · Keuskupan Agung Medan
