# 🔌 IOT — Dokumentasi Modul Nomor Lagu Digital

> **eKatolik — Paroki Santo Fidelis Parapat**
> IoT: ESP32 · Protokol: MQTT / HTTP REST

---

## 📋 Daftar Isi

1. [Gambaran Umum](#-gambaran-umum)
2. [Alur Komunikasi Sistem](#-alur-komunikasi-sistem)
3. [Spesifikasi Hardware (Rekomendasi)](#-spesifikasi-hardware-rekomendasi)
4. [Protokol Komunikasi](#-protokol-komunikasi)
5. [Cara Testing Tanpa Hardware (Mock/Simulator)](#-cara-testing-tanpa-hardware-mocksimulator)

---

## 💡 Gambaran Umum

Modul **IoT Nomor Lagu Digital** adalah sistem terintegrasi yang menggantikan papan nomor lagu manual dengan display digital LED cerdas. Fitur ini memungkinkan petugas gereja (dirigen/organis) untuk mengirimkan 4 digit nomor lagu dari aplikasi mobile eKatolik langsung ke layar digital yang terpasang di dinding gereja, tanpa perlu repot menggunakan remote kabel atau membalik angka secara manual.

ESP32 bertindak sebagai mikrokontroler utama yang menerima sinyal dari server backend dan mengendalikan panel display LED matriks (seperti P10) atau modul 7-segment besar.

---

## 🔄 Alur Komunikasi Sistem

Sistem menggunakan alur dari ujung ke ujung (end-to-end) sebagai berikut:

```
📱 Flutter Mobile App (Petugas)
        │
        │ 1. Kirim request JSON (misal: "nomor": "0314")
        ▼
🖥️ Laravel API Backend
        │
        │ 2. Simpan ke database (table `song_numbers`)
        │ 3. Forward data via protokol MQTT atau HTTP
        ▼
🌐 MQTT Broker / HTTP Server
        │
        │ 4. Push data (MQTT) atau di-poll/di-pull (HTTP)
        ▼
🔌 ESP32 Microcontroller (Gereja)
        │
        │ 5. Parse payload (nomor lagu "0314")
        ▼
📟 Display Digital LED
        (Layar menampilkan 0314 secara real-time)
```

---

## ⚙️ Spesifikasi Hardware (Rekomendasi)

Untuk mengimplementasikan alat ini di gereja, direkomendasikan menggunakan komponen berikut:

| Komponen | Deskripsi / Tipe | Peran |
|---|---|---|
| **Microcontroller** | **ESP32** (NodeMCU / WROOM) | Otak utama. Memiliki koneksi Wi-Fi built-in untuk terhubung ke router gereja. |
| **Display Panel** | Modul **LED Matrix P10** (Merah/RGB) atau **7-Segment Display** ukuran besar. | Menampilkan output visual angka. |
| **Power Supply** | Adaptor 5V / 12V (Sesuai kebutuhan panel LED) dengan arus minimal 5A. | Menyuplai daya ke ESP32 dan panel display secara stabil. |
| **Kabel Jumper** | Kabel secukupnya. | Penghubung ESP32 ke modul display. |
| **Casing** | Box akrilik atau kayu custom. | Melindungi komponen dari debu dan kelembapan. |

---

## 📡 Protokol Komunikasi

Terdapat dua opsi protokol komunikasi antara Laravel Backend dan ESP32. Disarankan menggunakan **MQTT** untuk respons *real-time* yang lebih cepat dan efisien.

### Opsi 1: MQTT (Direkomendasikan)

ESP32 bertindak sebagai *Subscriber* yang terus me-listen ke broker MQTT. Laravel bertindak sebagai *Publisher*.

*   **Broker**: Mosquitto (lokal) atau HiveMQ Cloud (publik).
*   **Topic**: `ekatoli/song_number`
*   **Payload (JSON)**:
    ```json
    {
      "nomor": "0314",
      "timestamp": 1704067200
    }
    ```

**Alur Kerja MQTT:**
1.  Admin mengubah lagu via Flutter.
2.  Laravel mem-publish payload ke `ekatoli/song_number`.
3.  ESP32, yang sudah men-subscribe topik tersebut, langsung menerima pesan dan merubah layar dalam hitungan milidetik.

### Opsi 2: HTTP REST (Polling)

ESP32 bertindak sebagai *Client* yang secara berkala meminta data (polling) ke endpoint Laravel.

*   **Endpoint Backend**: `GET http://<domain-backend>/api/v1/song-numbers/latest`
*   **Frekuensi Polling**: Setiap 3 - 5 detik.
*   **Response Backend**:
    ```json
    {
      "success": true,
      "data": {
        "nomor": "0314",
        "created_at": "2024-01-01T10:00:00.000000Z"
      }
    }
    ```

---

## 🧪 Cara Testing Tanpa Hardware (Mock/Simulator)

Saat proses *development*, Anda mungkin tidak memiliki fisik ESP32. Anda bisa mensimulasikannya menggunakan aplikasi pihak ketiga.

### Jika Menggunakan MQTT (Simulator MQTT.fx / MQTT Explorer)

1.  Jalankan aplikasi MQTT Explorer di PC Anda.
2.  Terhubunglah ke broker yang sama dengan konfigurasi Laravel (misal `localhost:1883`).
3.  Di aplikasi MQTT Explorer, masukkan topik `ekatoli/song_number` ke dalam kolom **Subscribe**.
4.  Buka aplikasi Flutter, masukkan nomor lagu dan tekan kirim.
5.  Perhatikan MQTT Explorer. Jika muncul pesan berisi nomor lagu yang Anda ketik, maka sistem Backend berfungsi dengan benar!

### Jika Menggunakan HTTP REST

1.  Pastikan aplikasi Backend Laravel sedang berjalan (`php artisan serve`).
2.  Buka browser web atau aplikasi Postman.
3.  Akses URL: `http://localhost:8000/api/v1/song-numbers/latest`.
4.  Coba kirim lagu dari Flutter, lalu *refresh* halaman browser/Postman. Anda akan melihat nomor lagu berubah secara real-time di JSON response.

---

> 📄 Lihat juga: [README.md](../README.md) · [BACKEND.md](BACKEND.md) · [DATABASE.md](DATABASE.md) · [MOBILE.md](MOBILE.md)
>
> ✝️ **eKatolik** — Paroki Santo Fidelis Parapat · Keuskupan Agung Medan
