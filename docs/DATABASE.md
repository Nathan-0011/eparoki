# 🗄️ DATABASE — Dokumentasi Skema Database

> **eKatolik — Paroki Santo Fidelis Parapat**
> Database: MySQL 8.0 · ORM: Laravel Eloquent

---

## 📋 Daftar Isi

1. [ERD (Entity Relationship Diagram)](#-erd-entity-relationship-diagram)
2. [Penjelasan Setiap Tabel](#-penjelasan-setiap-tabel)
3. [Relasi Antar Tabel](#-relasi-antar-tabel)
4. [Catatan Khusus](#-catatan-khusus)

---

## 🗺️ ERD (Entity Relationship Diagram)

```
┌─────────────┐          ┌──────────────────┐          ┌──────────────────┐
│    users    │          │   umat_users     │          │   lingkungan     │
│─────────────│          │──────────────────│          │──────────────────│
│ id (PK)     │          │ id (PK)          │          │ id (PK)          │
│ name        │          │ name             │          │ nama_lingkungan  │
│ email       │          │ email            │          │ nama_pelindung   │
│ password    │          │ google_id        │          │ ketua            │
│ role        │          │ avatar           │          │ created_at       │
│ created_at  │          │ token            │          │ updated_at       │
│ updated_at  │          │ created_at       │          └────────┬─────────┘
└─────────────┘          │ updated_at       │                   │ 1
     (Admin)             └──────────────────┘                   │
                              (Umat/OAuth)                       │ has many
                                                                 │
                                                        ┌────────▼─────────┐
                                                        │  kepala_keluarga │
                                                        │──────────────────│
                                                        │ id (PK)          │
                                                        │ lingkungan_id(FK)│
                                                        │ nama_kepala      │
                                                        │ alamat           │
                                                        │ no_telepon       │
                                                        │ jumlah_anggota   │
                                                        │ created_at       │
                                                        │ updated_at       │
                                                        └──────────────────┘

┌──────────────────────┐         ┌─────────────────────────┐
│ liturgical_calendars │         │     jadwal_ibadah        │
│──────────────────────│ 1  has  │─────────────────────────│
│ id (PK)              │────────►│ id (PK)                 │
│ tahun                │  many   │ liturgical_calendar_id  │ (FK, nullable)
│ minggu_ke            │         │ hari                    │
│ tanggal_mulai        │         │ jam                     │
│ tanggal_selesai      │         │ nama_ibadah             │
│ tema                 │         │ lokasi                  │
│ keterangan           │         │ week_of                 │
│ warna_liturgi        │         │ created_at              │
│ created_at           │         │ updated_at              │
│ updated_at           │         └─────────────────────────┘
└──────────────────────┘

┌──────────────┐         ┌─────────────────┐
│   pastors    │         │   bph_members   │
│──────────────│         │─────────────────│
│ id (PK)      │         │ id (PK)         │
│ nama         │         │ nama            │
│ foto         │         │ jabatan         │
│ biografi     │         │ foto            │
│ periode_dari │         │ periode_dari    │
│ periode_ke   │         │ periode_ke      │
│ is_active    │         │ is_active       │
│ created_at   │         │ created_at      │
│ updated_at   │         │ updated_at      │
└──────────────┘         └─────────────────┘
  (Berdiri sendiri)        (Berdiri sendiri)

┌──────────────┐         ┌─────────────────┐         ┌─────────────────┐
│   banners    │         │  intensi_misa   │         │  song_numbers   │
│──────────────│         │─────────────────│         │─────────────────│
│ id (PK)      │         │ id (PK)         │         │ id (PK)         │
│ judul        │         │ nama_keluarga   │         │ nomor_lagu      │
│ gambar       │         │ nominal         │         │ dikirim_oleh    │
│ start_date   │         │ keterangan      │         │ status_kirim    │
│ end_date     │         │ week_of         │         │ sent_at         │
│ is_active    │         │ created_at      │         │ created_at      │
│ created_at   │         │ updated_at      │         │ updated_at      │
│ updated_at   │         └─────────────────┘         └─────────────────┘
└──────────────┘           (Auto-reset mingguan)       (IoT log)
```

---

## 📊 Penjelasan Setiap Tabel

---

### 1. `users` — Admin Paroki

Menyimpan data pengguna admin yang mengelola konten via web panel.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key, auto increment |
| `name` | VARCHAR(255) | No | Nama lengkap admin |
| `email` | VARCHAR(255) | No | Email (unik) |
| `password` | VARCHAR(255) | No | Hash bcrypt |
| `role` | ENUM('superadmin','admin') | No | Level akses |
| `email_verified_at` | TIMESTAMP | Yes | Verifikasi email |
| `remember_token` | VARCHAR(100) | Yes | Remember me token |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

### 2. `umat_users` — Umat Paroki (Google OAuth)

Menyimpan data umat yang login menggunakan Google OAuth via Laravel Socialite.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `name` | VARCHAR(255) | No | Nama dari Google |
| `email` | VARCHAR(255) | No | Email Google (unik) |
| `google_id` | VARCHAR(255) | No | ID unik dari Google |
| `avatar` | TEXT | Yes | URL foto profil Google |
| `token` | TEXT | Yes | Sanctum API token |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

### 3. `lingkungan` — Wilayah Lingkungan Paroki

Menyimpan data lingkungan-lingkungan dalam satu paroki (±10 lingkungan).

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `nama_lingkungan` | VARCHAR(255) | No | Nama lingkungan (cth: Santo Antonius) |
| `nama_pelindung` | VARCHAR(255) | Yes | Santo pelindung lingkungan |
| `ketua` | VARCHAR(255) | Yes | Nama ketua lingkungan |
| `no_telepon_ketua` | VARCHAR(20) | Yes | Kontak ketua |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

**Contoh data:**

| id | nama_lingkungan | nama_pelindung |
|---|---|---|
| 1 | Lingkungan Santo Antonius | Santo Antonius Padua |
| 2 | Lingkungan Santo Tomas | Santo Tomas Rasul |
| 3 | Lingkungan Santa Maria | Santa Maria |

---

### 4. `kepala_keluarga` — Data Keluarga per Lingkungan

Menyimpan data kepala keluarga (KK) dalam setiap lingkungan.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `lingkungan_id` | BIGINT UNSIGNED | No | **FK → lingkungan.id** |
| `nama_kepala` | VARCHAR(255) | No | Nama kepala keluarga |
| `nama_pasangan` | VARCHAR(255) | Yes | Nama pasangan |
| `alamat` | TEXT | Yes | Alamat rumah |
| `no_telepon` | VARCHAR(20) | Yes | Nomor HP |
| `jumlah_anggota` | INT | No | Jumlah anggota keluarga |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

### 5. `liturgical_calendars` — Kalender Liturgi

Menyimpan tema dan keterangan liturgi per minggu.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `tahun` | YEAR | No | Tahun liturgi |
| `minggu_ke` | TINYINT | No | Minggu ke-1 s/d 52 |
| `tanggal_mulai` | DATE | No | Hari Senin minggu tsb |
| `tanggal_selesai` | DATE | No | Hari Minggu minggu tsb |
| `tema` | VARCHAR(500) | No | Tema liturgi minggu ini |
| `keterangan` | TEXT | Yes | Penjelasan lebih detail |
| `warna_liturgi` | ENUM('hijau','merah','putih','ungu','merah_muda','hitam') | No | Warna liturgi |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

### 6. `jadwal_ibadah` — Jadwal Misa & Ibadah

Menyimpan jadwal misa harian dan mingguan.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `liturgical_calendar_id` | BIGINT UNSIGNED | Yes | **FK → liturgical_calendars.id** |
| `week_of` | DATE | No | Senin awal minggu jadwal berlaku |
| `hari` | ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') | No | Hari ibadah |
| `jam` | TIME | No | Jam mulai ibadah |
| `nama_ibadah` | VARCHAR(255) | No | Nama (cth: Misa Harian, Misa Minggu) |
| `lokasi` | VARCHAR(255) | Yes | Lokasi/kapel |
| `keterangan` | TEXT | Yes | Catatan tambahan |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

### 7. `pastors` — Riwayat Pastor Paroki

Menyimpan daftar pastor yang pernah dan sedang bertugas.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `nama` | VARCHAR(255) | No | Nama lengkap pastor |
| `foto` | VARCHAR(500) | Yes | Path foto di storage |
| `biografi` | TEXT | Yes | Riwayat singkat |
| `periode_dari` | YEAR | No | Tahun mulai bertugas |
| `periode_ke` | YEAR | Yes | Tahun selesai (NULL = masih aktif) |
| `is_active` | BOOLEAN | No | Status aktif saat ini |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

### 8. `bph_members` — Badan Pengurus Harian

Menyimpan anggota BPH paroki aktif dan historis.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `nama` | VARCHAR(255) | No | Nama anggota BPH |
| `jabatan` | VARCHAR(255) | No | Jabatan (Ketua, Wakil, Bendahara, dll.) |
| `foto` | VARCHAR(500) | Yes | Path foto |
| `periode_dari` | YEAR | No | Tahun mulai |
| `periode_ke` | YEAR | Yes | Tahun selesai (NULL = aktif) |
| `is_active` | BOOLEAN | No | Status periode aktif |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

### 9. `banners` — Banner Kegiatan

Menyimpan gambar banner event yang tampil sebagai carousel di beranda mobile.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `judul` | VARCHAR(255) | No | Judul banner/event |
| `gambar` | VARCHAR(500) | No | Path gambar di storage |
| `start_date` | DATE | No | Tanggal mulai aktif |
| `end_date` | DATE | No | Tanggal kadaluarsa |
| `is_active` | BOOLEAN | No | Toggle aktif manual |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

> **Query aktif:** `WHERE is_active = 1 AND start_date <= NOW() AND end_date >= NOW()`

---

### 10. `intensi_misa` — Intensi Misa Mingguan

Menyimpan intensi misa yang diinput admin untuk ditampilkan minggu berjalan.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `nama_keluarga` | VARCHAR(255) | No | Nama keluarga pengintens |
| `nominal` | BIGINT | Yes | Nominal intensi (Rp) |
| `keterangan` | TEXT | Yes | Keterangan intensi |
| `week_of` | DATE | No | Senin awal minggu intensi berlaku |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

> **Query minggu berjalan:** `WHERE week_of = DATE(NOW() - INTERVAL WEEKDAY(NOW()) DAY)`

---

### 11. `song_numbers` — Log Nomor Lagu (IoT)

Menyimpan riwayat nomor lagu yang pernah dikirim ke perangkat IoT.

| Kolom | Tipe | Nullable | Deskripsi |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `nomor_lagu` | VARCHAR(4) | No | Nomor lagu 4 digit |
| `dikirim_oleh` | VARCHAR(255) | Yes | Nama/ID petugas pengirim |
| `status_kirim` | ENUM('success','failed','pending') | No | Status pengiriman ke IoT |
| `response_iot` | TEXT | Yes | Response dari perangkat IoT |
| `sent_at` | TIMESTAMP | Yes | Waktu dikirim ke IoT |
| `created_at` | TIMESTAMP | Yes | — |
| `updated_at` | TIMESTAMP | Yes | — |

---

## 🔗 Relasi Antar Tabel

| Tabel Induk | Tabel Anak | Jenis Relasi | Foreign Key |
|---|---|---|---|
| `lingkungan` | `kepala_keluarga` | One-to-Many (1:N) | `kepala_keluarga.lingkungan_id` |
| `liturgical_calendars` | `jadwal_ibadah` | One-to-Many (1:N) | `jadwal_ibadah.liturgical_calendar_id` |
| `users` | *(Tidak ada anak)* | — | Admin berdiri sendiri |
| `umat_users` | *(Tidak ada anak)* | — | Umat berdiri sendiri |
| `pastors` | *(Tidak ada anak)* | — | Berdiri sendiri |
| `bph_members` | *(Tidak ada anak)* | — | Berdiri sendiri |
| `banners` | *(Tidak ada anak)* | — | Berdiri sendiri |
| `intensi_misa` | *(Tidak ada anak)* | — | Berdiri sendiri |
| `song_numbers` | *(Tidak ada anak)* | — | Berdiri sendiri |

### Diagram Relasi Ringkas

```
lingkungan ──(1:N)──► kepala_keluarga
liturgical_calendars ──(1:N)──► jadwal_ibadah
```

---

## 📝 Catatan Khusus

### ♻️ Auto-Reset Intensi Misa

- Intensi misa **tidak pernah dihapus** dari database
- Sistem menggunakan kolom `week_of` (DATE, Senin awal minggu) sebagai penanda periode
- Setiap Senin pukul 00:00 WIB, scheduler Laravel menambahkan entri baru dengan `week_of` minggu berjalan
- Query mobile hanya mengambil baris dengan `week_of` = Senin minggu ini
- Data lama tetap ada sebagai **arsip historis**

```sql
-- Query intensi minggu berjalan
SELECT * FROM intensi_misa
WHERE week_of = DATE(NOW() - INTERVAL WEEKDAY(NOW()) DAY)
ORDER BY created_at ASC;
```

---

### 🖼️ Filter Banner Aktif

Banner ditampilkan di mobile hanya jika memenuhi 3 kondisi:

```sql
SELECT * FROM banners
WHERE is_active = 1
  AND start_date <= CURDATE()
  AND end_date >= CURDATE()
ORDER BY start_date DESC;
```

---

### 🎵 Status IoT Nomor Lagu

Kolom `status_kirim` pada tabel `song_numbers` memiliki 3 nilai:

| Status | Arti |
|---|---|
| `pending` | Baru dibuat, belum dikirim ke IoT |
| `success` | Berhasil dikirim dan diterima ESP32 |
| `failed` | Gagal dikirim (timeout / IoT offline) |

---

### 👨‍⚕️ Pastor & BPH Aktif

- Kolom `is_active = 1` menandai entitas yang sedang aktif
- Kolom `periode_ke = NULL` berarti **masih menjabat saat ini**
- Bisa ada lebih dari 1 BPH aktif (untuk berbagai jabatan berbeda)
- Hanya boleh ada **1 pastor aktif** dalam satu waktu

```sql
-- Pastor aktif saat ini
SELECT * FROM pastors WHERE is_active = 1 LIMIT 1;

-- Struktur BPH periode aktif
SELECT * FROM bph_members WHERE is_active = 1 ORDER BY jabatan;
```

---

### 🗓️ Warna Liturgi

Kolom `warna_liturgi` pada `liturgical_calendars` menggunakan ENUM:

| Nilai | Warna | Makna Liturgi |
|---|---|---|
| `hijau` | 🟢 Hijau | Masa Biasa |
| `merah` | 🔴 Merah | Minggu Palma, Pentakosta, Pesta Martir |
| `putih` | ⚪ Putih | Natal, Paskah, Pesta Tuhan |
| `ungu` | 🟣 Ungu | Adven, Prapaskah |
| `merah_muda` | 🩷 Merah Muda | Minggu Gaudete & Laetare |
| `hitam` | ⚫ Hitam | Misa Requiem / Arwah |

---

> 📄 Lihat juga: [BACKEND.md](BACKEND.md) · [MOBILE.md](MOBILE.md) · [IOT.md](IOT.md)
>
> ✝️ **eKatolik** — Paroki Santo Fidelis Parapat · Keuskupan Agung Medan
