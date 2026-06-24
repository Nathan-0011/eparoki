# 📱 MOBILE — Dokumentasi Teknis Flutter

> **eKatolik — Paroki Santo Fidelis Parapat**
> Mobile: Flutter 3.x · Bahasa: Dart

---

## 📋 Daftar Isi

1. [Gambaran Arsitektur](#-gambaran-arsitektur)
2. [Penjelasan State Management](#-penjelasan-state-management)
3. [Struktur Navigasi](#-struktur-navigasi)
4. [Penjelasan Halaman (Screens)](#-penjelasan-halaman-screens)
5. [Cara Setup & Menjalankan](#-cara-setup--menjalankan)
6. [Konfigurasi API (Base URL)](#-konfigurasi-api-base-url)

---

## 🏗️ Gambaran Arsitektur

Aplikasi mobile eKatolik dibangun menggunakan **Flutter** dengan pendekatan arsitektur **Clean Architecture (Layered)**. Hal ini bertujuan untuk memisahkan UI (Presentation) dari logika bisnis dan manajemen data, sehingga kode lebih mudah diuji, dipelihara, dan dikembangkan.

### Lapisan (Layers)

1.  **Presentation Layer (`lib/presentation/`)**:
    *   Berisi komponen UI (Screens/Pages, Widgets).
    *   Tugasnya hanya menampilkan data ke pengguna dan menerima input (seperti klik tombol).
    *   **Tidak boleh** berisi logika pemanggilan API secara langsung.
2.  **Domain/State Management Layer**:
    *   Penghubung antara Presentation dan Data Layer.
    *   Menangani logika bisnis aplikasi (mengubah status *loading*, *success*, *error*).
3.  **Data Layer (`lib/data/`)**:
    *   **Repositories**: Kelas yang bertanggung jawab mengambil data, entah itu dari API (jaringan) atau dari database lokal (jika ada).
    *   **Models**: Kelas representasi struktur data (Data Object/Entity) yang diterima dari API, lengkap dengan metode konversi JSON (e.g., `fromJson`, `toJson`).
4.  **Core Layer (`lib/core/`)**:
    *   Berisi konfigurasi global: URL API, warna tema, utilitas, fungsi helper, dan ekstensi yang digunakan di berbagai bagian aplikasi.

---

## ⚙️ Penjelasan State Management

Aplikasi ini menggunakan manajemen *state* (disesuaikan dengan implementasi Anda, contoh menggunakan **Provider** atau **Bloc/Cubit** — *disini kita asumsikan menggunakan Provider untuk kesederhanaan, atau Cubit/Bloc sesuai standar umum*).

*(Jika menggunakan BLoC/Cubit, pendekatannya adalah sebagai berikut:)*

Setiap fitur memiliki **Cubit/Bloc** sendiri untuk mengelola *state* spesifiknya:

*   **State Class**: Mendefinisikan status data saat ini (misal: `KalenderInitial`, `KalenderLoading`, `KalenderLoaded`, `KalenderError`).
*   **Cubit Class**: Memiliki metode untuk mengambil data dari Repository dan meng-*emit* (memancarkan) state baru. UI akan me-listen perubahan *state* ini.

**Contoh Alur Data (Kalender Liturgi):**
1.  UI memanggil `context.read<KalenderCubit>().fetchKalender()`.
2.  `KalenderCubit` meng-*emit* `KalenderLoading`.
3.  UI merespon dengan menampilkan `CircularProgressIndicator`.
4.  `KalenderCubit` meminta data ke `KalenderRepository`.
5.  `KalenderRepository` melakukan HTTP GET ke API Laravel.
6.  Jika sukses, API mengembalikan JSON. Repository mengubah JSON menjadi List of `LiturgicalCalendar` model.
7.  `KalenderCubit` menerima data model dan meng-*emit* `KalenderLoaded(data)`.
8.  UI merespon dengan menampilkan kalender dan data liturgi.

---

## 🧭 Struktur Navigasi

Navigasi utama menggunakan **Bottom Navigation Bar** yang selalu terlihat di sebagian besar layar utama.

### Main Navigation (BottomBar)

| Tab Index | Ikon | Label | Tujuan / Screen | Deskripsi |
| :---: | :--- | :--- | :--- | :--- |
| **0** | 🏠 | Beranda | `HomeScreen` | Dashboard, Banner, Ringkasan |
| **1** | 📅 | Kalender | `KalenderScreen` | Kalender Liturgi Interaktif |
| **2** | 🕊️ | Jadwal | `JadwalScreen` | Jadwal Ibadah Mingguan/Harian |
| **3** | 👥 | Umat | `UmatScreen` | Data Lingkungan & KK |
| **4** | 👤 | Profil | `ProfileScreen` | Profil Pastor, BPH, & Akun |

*(Navigasi antar halaman detail biasanya di-push ke atas *stack* sehingga memunculkan tombol Back)*.

---

## 📱 Penjelasan Halaman (Screens)

### 1. `HomeScreen` (Beranda)
*   Menampilkan sapaan umat.
*   **Carousel Banner**: Menampilkan banner kegiatan terbaru dari API.
*   **Quick Menu/Shortcut**: Tombol akses cepat ke Intensi Misa atau Nomor Lagu (jika user punya akses/login).

### 2. `KalenderScreen` (Kalender Liturgi)
*   Menampilkan UI kalender bulan berjalan.
*   Setiap hari atau minggu bisa ditap.
*   Saat minggu dipilih, akan memunculkan bottom sheet atau kartu detail berisi: Tema Liturgi, Warna Liturgi, dan Keterangan.

### 3. `JadwalScreen` (Jadwal Ibadah)
*   Menampilkan daftar jadwal ibadah.
*   Memiliki tab/filter untuk memisahkan Misa Harian vs Misa Minggu/Hari Raya.
*   Berkaitan dengan minggu liturgi yang sedang berjalan.

### 4. `UmatScreen` (Data Lingkungan)
*   **Daftar Lingkungan**: Menampilkan daftar ±10 lingkungan paroki.
*   **Detail Lingkungan (Tap)**: Menampilkan info ketua lingkungan dan daftar Kepala Keluarga (KK) di lingkungan tersebut.

### 5. `ProfileScreen` (Profil & Akun)
*   Menu untuk melihat **Riwayat Pastor** paroki.
*   Menu untuk melihat **Susunan BPH** paroki aktif.
*   Tombol Login/Logout (Google Sign-In) untuk umat.

### 6. Fitur Ekstra (Akses Tertentu/Shortcut)
*   **`IntensiScreen`**: Form tampilan list intensi misa minggu ini.
*   **`SongNumberScreen`**: (Khusus petugas/admin) Form keypad angka sederhana (0-9) untuk menginput 4 digit nomor lagu, lalu tombol "Kirim ke Layar (IoT)".

---

## 🚀 Cara Setup & Menjalankan

### Persyaratan
1.  Flutter SDK (versi 3.x)
2.  Android Studio (untuk emulator Android) atau Xcode (untuk iOS)
3.  VS Code (disarankan untuk *coding*)

### Langkah-langkah

```bash
# 1. Buka folder eparoki_android
cd eparoki_android

# 2. Unduh semua dependensi (packages)
flutter pub get

# 3. Pastikan device/emulator terhubung
flutter devices

# 4. Jalankan aplikasi (Mode Debug)
flutter run

# 5. Build APK Release (untuk diinstal di HP Android)
flutter build apk --release
# File APK akan berada di: build/app/outputs/flutter-apk/app-release.apk
```

---

## 🔌 Konfigurasi API (Base URL)

Untuk menghubungkan aplikasi Flutter dengan Backend Laravel, Anda harus mengonfigurasi URL server.

1.  Buka file: `lib/core/constants/api_constants.dart` (sesuaikan dengan struktur file Anda).
2.  Cari variabel `baseUrl` (atau yang serupa).

### Jika Backend di Localhost (XAMPP/Laravel Serve)
Jika Anda menguji di emulator Android dan backend berjalan di `localhost:8000`:

```dart
// Untuk Emulator Android bawaan
const String BASE_URL = "http://10.0.2.2:8000/api/v1";

// Jika menggunakan physical device, gunakan IP LAN komputer Anda
// Pastikan HP dan Laptop di WiFi yang sama
const String BASE_URL = "http://192.168.1.100:8000/api/v1";
```

### Jika Backend Sudah di Server (Production)

```dart
const String BASE_URL = "https://ekatolik-fidelis.com/api/v1";
```

*Catatan: Pastikan endpoint URL diakhiri tanpa tanda slash `/` di akhir jika setup route mengharuskan, atau ikuti standar konfigurasi file tersebut.*

---

> 📄 Lihat juga: [README.md](../README.md) · [BACKEND.md](BACKEND.md) · [DATABASE.md](DATABASE.md)
>
> ✝️ **eKatolik** — Paroki Santo Fidelis Parapat · Keuskupan Agung Medan
