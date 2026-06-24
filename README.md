# ✝️ eKatolik — Aplikasi Informasi Digital Paroki

> **Paroki Santo Fidelis Parapat, Danau Toba, Sumatera Utara**
> **Keuskupan Agung Medan**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Flutter](https://img.shields.io/badge/Flutter-3.x-02569B?style=for-the-badge&logo=flutter&logoColor=white)](https://flutter.dev)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

---

## 📖 Gambaran Umum

**eKatolik** adalah platform informasi digital terintegrasi yang dirancang khusus untuk mendukung kehidupan rohani dan operasional administratif di Paroki Santo Fidelis, Parapat, Danau Toba. Sistem ini hadir sebagai solusi modern untuk mengelola dan menyebarluaskan informasi paroki secara terpusat — mulai dari kalender liturgi, jadwal ibadah, data umat, profil pastor & BPH, hingga intensi misa — yang dapat diakses dengan mudah oleh seluruh umat kapan saja dan di mana saja melalui smartphone mereka.

Platform ini terdiri dari dua komponen utama yang saling terintegrasi: **Web Admin berbasis Laravel 11** sebagai pusat pengelolaan konten dan penyedia API, serta **aplikasi mobile Flutter** yang memungkinkan umat paroki mengakses informasi secara real-time. Lebih dari sekadar sistem informasi, eKatolik juga dilengkapi modul **IoT Nomor Lagu Digital** berbasis ESP32 yang menghubungkan aplikasi mobile langsung ke layar display fisik di dalam gereja — sehingga petugas liturgi dapat mengirimkan nomor lagu secara nirkabel dari genggaman tangan mereka.

---

## ✨ Fitur Utama

- 📅 **Kalender Liturgi** — Admin input tema per tahun/bulan/minggu; tampil interaktif di mobile
- 🕊️ **Jadwal Ibadah** — CRUD jadwal harian & mingguan, terhubung ke kalender liturgi
- 👥 **Data Umat** — Manajemen hierarki Paroki → Lingkungan → Kepala Keluarga
- 👨‍⚕️ **Profil Pastor & BPH** — Riwayat pastor, struktur kepengurusan aktif, foto & biografi
- 🖼️ **Banner Kegiatan** — Carousel banner event (Paskah, Natal, dll.) dengan tanggal kadaluarsa
- 🙏 **Intensi Misa** — Input intensi keluarga, tampil per minggu, auto-reset setiap Senin 00:00 WIB
- 🎵 **Nomor Lagu (IoT)** — Kirim nomor lagu 4-digit: Mobile → API → ESP32 → Display digital gereja
- 🔐 **Autentikasi Ganda** — Admin via email/password, Umat via Google OAuth (Socialite)

---

## 🛠️ Tech Stack

| Layer | Teknologi | Versi | Keterangan |
|---|---|---|---|
| **Backend Framework** | Laravel | 11.x | MVC + REST API |
| **Bahasa Backend** | PHP | 8.2+ | — |
| **Database** | MySQL | 8.0 | Via XAMPP |
| **Autentikasi API** | Laravel Sanctum | — | Token-based auth |
| **OAuth** | Laravel Socialite | — | Google Sign-In |
| **Penjadwalan** | Laravel Scheduler | — | Auto-reset intensi |
| **Mobile Framework** | Flutter | 3.x | Android & iOS |
| **Bahasa Mobile** | Dart | 3.x | — |
| **IoT Mikrokontroler** | ESP32 | — | Display nomor lagu |
| **Protokol IoT** | MQTT / HTTP REST | — | Komunikasi real-time |
| **Dev Server** | XAMPP | — | Apache + MySQL lokal |
| **Package Manager** | Composer + pub | — | PHP & Dart |
| **Build Tool** | Vite | — | Asset bundling |
| **IDE** | VS Code | — | — |
| **Version Control** | Git | — | — |

---

## 📁 Struktur Folder Project

```
eparoki/                              # 📦 Root repository
│
├── README.md                         # ← File ini
├── docs/                             # 📚 Dokumentasi teknis
│   ├── BACKEND.md                    # Arsitektur & API backend
│   ├── DATABASE.md                   # Skema & ERD database
│   ├── MOBILE.md                     # Arsitektur & navigasi Flutter
│   └── IOT.md                        # Spesifikasi modul IoT
│
├── eparoki-platform/                 # 🖥️ Backend Laravel 11
│   ├── app/
│   │   ├── Console/                  # Artisan commands & scheduler
│   │   │   └── Commands/
│   │   │       └── ResetIntensiMisa.php
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/              # Controller API (mobile)
│   │   │   │   │   ├── AuthController.php
│   │   │   │   │   ├── BannerController.php
│   │   │   │   │   ├── BphController.php
│   │   │   │   │   ├── IntensiMisaController.php
│   │   │   │   │   ├── JadwalIbadahController.php
│   │   │   │   │   ├── KepalaKeluargaController.php
│   │   │   │   │   ├── LingkunganController.php
│   │   │   │   │   ├── LiturgicalCalendarController.php
│   │   │   │   │   ├── PastorController.php
│   │   │   │   │   └── SongNumberController.php
│   │   │   │   └── Web/              # Controller web admin
│   │   │   ├── Middleware/
│   │   │   └── Requests/             # Form request validation
│   │   ├── Models/                   # Eloquent ORM models
│   │   └── Services/                 # Business logic layer
│   ├── bootstrap/
│   ├── config/                       # app, auth, database, mqtt, dll.
│   ├── database/
│   │   ├── migrations/               # Skema migrasi tabel
│   │   └── seeders/                  # Data awal / dummy
│   ├── public/                       # Entry point web (index.php)
│   ├── resources/
│   │   ├── views/                    # Blade template (admin panel)
│   │   └── js/ & css/                # Frontend assets
│   ├── routes/
│   │   ├── api.php                   # API routes → mobile
│   │   └── web.php                   # Web routes → admin panel
│   ├── storage/
│   │   └── app/public/               # Foto upload, banner, dll.
│   ├── tests/
│   ├── .env.example
│   ├── composer.json
│   └── vite.config.js
│
└── eparoki_android/                  # 📱 Mobile Flutter
    ├── android/                      # Native Android (gradle, manifest)
    ├── ios/                          # Native iOS (xcode config)
    ├── lib/
    │   ├── core/
    │   │   ├── constants/            # API URL, warna, string
    │   │   ├── theme/                # App theme & typography
    │   │   └── utils/                # Helper functions
    │   ├── data/
    │   │   ├── models/               # Data class / entity
    │   │   └── repositories/         # API service layer
    │   ├── presentation/
    │   │   ├── screens/              # Halaman UI utama
    │   │   │   ├── home/
    │   │   │   ├── kalender/
    │   │   │   ├── jadwal/
    │   │   │   ├── umat/
    │   │   │   ├── intensi/
    │   │   │   ├── pastor/
    │   │   │   └── lagu/
    │   │   └── widgets/              # Komponen UI reusable
    │   └── main.dart                 # Entry point
    ├── test/
    ├── pubspec.yaml
    └── analysis_options.yaml
```

---

## 🚀 Cara Setup & Instalasi

### ✅ Prasyarat

| Tool | Versi Minimal | Cara Cek |
|---|---|---|
| PHP | 8.2+ | `php -v` |
| Composer | 2.x | `composer --version` |
| MySQL | 8.0 | Via XAMPP |
| Node.js | 18.x | `node -v` |
| Flutter SDK | 3.x | `flutter --version` |
| Git | 2.x | `git --version` |

---

### 🖥️ Setup Backend (Laravel)

```bash
# 1. Clone repository
git clone https://github.com/your-org/eparoki.git
cd eparoki

# 2. Masuk ke folder backend
cd eparoki-platform

# 3. Install dependensi PHP
composer install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi file .env
nano .env
# (isi: DB_DATABASE, DB_USERNAME, DB_PASSWORD, GOOGLE_CLIENT_ID, dst.)

# 7. Buat database di MySQL
# Buka phpMyAdmin atau jalankan:
mysql -u root -p -e "CREATE DATABASE ekatoli_db;"

# 8. Jalankan migrasi database
php artisan migrate

# 9. (Opsional) Seed data awal
php artisan db:seed

# 10. Buat symbolic link storage (untuk akses file upload)
php artisan storage:link

# 11. Install & build frontend assets
npm install && npm run build
```

#### 📋 Konfigurasi `.env` yang Wajib Diisi

```env
APP_NAME="eKatolik"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekatoli_db
DB_USERNAME=root
DB_PASSWORD=

# Google OAuth (Laravel Socialite)
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# MQTT Broker (untuk fitur IoT Nomor Lagu)
MQTT_HOST=127.0.0.1
MQTT_PORT=1883
MQTT_TOPIC_SONG=ekatoli/song_number
MQTT_CLIENT_ID=laravel-backend

# Sanctum (API Auth)
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

---

### 📱 Setup Mobile (Flutter)

```bash
# 1. Masuk ke folder mobile
cd ../eparoki_android

# 2. Install dependensi Dart/Flutter
flutter pub get

# 3. Konfigurasi base URL API
# Edit: lib/core/constants/api_constants.dart
# Ganti BASE_URL dengan IP server backend Anda

# 4. Setup Google Sign-In (jika diperlukan)
# Letakkan google-services.json di: android/app/google-services.json
# Download dari: https://console.firebase.google.com

# 5. Periksa device/emulator yang tersedia
flutter devices

# 6. Jalankan aplikasi
flutter run
```

---

## ▶️ Cara Menjalankan Project

### Backend

```bash
cd eparoki-platform

# Jalankan development server Laravel
php artisan serve
# → Berjalan di: http://localhost:8000

# [Terminal terpisah] Jalankan scheduler (auto-reset intensi setiap Senin)
php artisan schedule:work

# [Terminal terpisah] Jalankan queue worker (jika pakai queued jobs)
php artisan queue:work --sleep=3 --tries=3
```

### Mobile

```bash
cd eparoki_android

# Mode debug
flutter run

# Build APK release
flutter build apk --release
# Output: build/app/outputs/flutter-apk/app-release.apk

# Build App Bundle (Play Store)
flutter build appbundle --release
```

---

## 👥 Kontributor

| Nama | Peran | Kontak |
|---|---|---|
| Tim Pengembang eKatolik | Full-Stack Developer | — |

> 💡 Proyek ini dikembangkan secara khusus untuk mendukung digitalisasi informasi paroki di **Paroki Santo Fidelis, Parapat**, Danau Toba, Sumatera Utara — di bawah naungan **Keuskupan Agung Medan**.

---

## 🔗 Dokumentasi Teknis

| Dokumen | Deskripsi |
|---|---|
| [📘 BACKEND.md](docs/BACKEND.md) | Arsitektur backend, API endpoint, setup Laravel |
| [🗄️ DATABASE.md](docs/DATABASE.md) | ERD, skema tabel, relasi antar entitas |
| [📱 MOBILE.md](docs/MOBILE.md) | Arsitektur Flutter, navigasi, state management |
| [🔌 IOT.md](docs/IOT.md) | Modul IoT nomor lagu, ESP32, protokol MQTT |

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License** — lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

```
MIT License — Copyright (c) 2024 eKatolik Development Team
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software...
```

---

<p align="center">
  ✝️ <strong>eKatolik</strong> — Aplikasi Informasi Digital Paroki<br>
  <strong>Paroki Santo Fidelis Parapat • Danau Toba • Keuskupan Agung Medan</strong><br><br>
  <em>"Melayani umat dengan teknologi, menjaga iman dengan tradisi"</em>
</p>
