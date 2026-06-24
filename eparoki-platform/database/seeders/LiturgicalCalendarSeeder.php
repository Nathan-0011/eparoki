<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LiturgicalCalendar;

class LiturgicalCalendarSeeder extends Seeder
{
    /**
     * Seed kalender liturgi Katolik untuk tahun 2025 (48 baris data).
     * Menggunakan updateOrCreate agar bisa dijalankan berulang tanpa duplikasi.
     */
    public function run(): void
    {
        $data = [
            // ======== JANUARI 2025 ========
            [1, 1, 'Hari Raya Maria Bunda Allah & Tahun Baru',
                'Hari Raya Maria Bunda Allah merupakan hari pertama tahun kalender. Warna liturgi: Putih.'],
            [1, 2, 'Pesta Pembaptisan Tuhan',
                'Menutup masa Natal. Yesus dibaptis oleh Yohanes Pembaptis di Sungai Yordan. Warna liturgi: Putih.'],
            [1, 3, 'Minggu Biasa II',
                'Bacaan: Yes 62:1-5; 1 Kor 12:4-11; Yoh 2:1-11. Warna liturgi: Hijau.'],
            [1, 4, 'Minggu Biasa III',
                'Bacaan: Neh 8:2-4a.5-6.8-10; 1 Kor 12:12-30; Luk 1:1-4; 4:14-21. Warna liturgi: Hijau.'],

            // ======== FEBRUARI 2025 ========
            [2, 1, 'Minggu Biasa IV',
                'Bacaan: Yer 1:4-5.17-19; 1 Kor 12:31–13:13; Luk 4:21-30. Warna liturgi: Hijau.'],
            [2, 2, 'Minggu Biasa V',
                'Bacaan: Yes 6:1-2a.3-8; 1 Kor 15:1-11; Luk 5:1-11. Warna liturgi: Hijau.'],
            [2, 3, 'Rabu Abu — Awal Masa Prapaskah',
                'Rabu Abu menandai awal Masa Prapaskah (40 hari sebelum Paskah). Umat menerima abu sebagai tanda pertobatan. Puasa dan pantang wajib dilaksanakan.'],
            [2, 4, 'Minggu Prapaskah I',
                'Bacaan: Ul 26:4-10; Rm 10:8-13; Luk 4:1-13. Yesus dicobai di padang gurun. Warna liturgi: Ungu.'],

            // ======== MARET 2025 ========
            [3, 1, 'Minggu Prapaskah II',
                'Bacaan: Kej 15:5-12.17-18; Fil 3:17–4:1; Luk 9:28b-36. Yesus dimuliakan di gunung. Warna liturgi: Ungu.'],
            [3, 2, 'Minggu Prapaskah III',
                'Bacaan: Kel 3:1-8a.13-15; 1 Kor 10:1-6.10-12; Luk 13:1-9. Warna liturgi: Ungu.'],
            [3, 3, 'Minggu Prapaskah IV (Laetare)',
                'Minggu Laetare — minggu sukacita di tengah masa Prapaskah. Warna liturgi: Merah Muda (Rose). Bacaan: Yos 5:9a.10-12; 2 Kor 5:17-21; Luk 15:1-3.11-32.'],
            [3, 4, 'Minggu Prapaskah V',
                'Bacaan: Yes 43:16-21; Fil 3:8-14; Yoh 8:1-11. Warna liturgi: Ungu.'],

            // ======== APRIL 2025 ========
            [4, 1, 'Minggu Palma — Pekan Suci',
                'Dimulai dengan prosesi palma memperingati masuknya Yesus ke Yerusalem. Bacaan Sengsara dari Injil Lukas. Warna liturgi: Merah.'],
            [4, 2, 'Triduum Paskah — Paskah',
                'Kamis Putih: Perjamuan Malam Terakhir. Jumat Agung: Perayaan Sengsara Tuhan. Sabtu Suci: Vigili Paskah. Minggu Paskah: Kebangkitan Tuhan. Warna liturgi: Putih & Emas.'],
            [4, 3, 'Minggu Paskah II — Minggu Kerahiman Ilahi',
                'Pesta Kerahiman Ilahi yang ditetapkan oleh St. Yohanes Paulus II. Bacaan: Kis 5:12-16; Why 1:9-11a.12-13.17-19; Yoh 20:19-31. Warna liturgi: Putih.'],
            [4, 4, 'Minggu Paskah III',
                'Bacaan: Kis 5:27b-32.40b-41; Why 5:11-14; Yoh 21:1-19. Warna liturgi: Putih.'],

            // ======== MEI 2025 ========
            [5, 1, 'Minggu Paskah IV — Minggu Panggilan',
                'Hari Doa Sedunia untuk Panggilan. Bacaan: Kis 13:14.43-52; Why 7:9.14b-17; Yoh 10:27-30. Warna liturgi: Putih.'],
            [5, 2, 'Minggu Paskah V',
                'Bacaan: Kis 14:21-27; Why 21:1-5a; Yoh 13:31-33a.34-35. Warna liturgi: Putih.'],
            [5, 3, 'Minggu Paskah VI',
                'Bacaan: Kis 15:1-2.22-29; Why 21:10-14.22-23; Yoh 14:23-29. Warna liturgi: Putih.'],
            [5, 4, 'Kenaikan Tuhan — Minggu Paskah VII',
                'Hari Raya Kenaikan Tuhan ke Surga. Bacaan: Kis 1:1-11; Ef 1:17-23; Luk 24:46-53. Warna liturgi: Putih.'],

            // ======== JUNI 2025 ========
            [6, 1, 'Hari Raya Pentakosta',
                'Pencurahan Roh Kudus atas para rasul. Penutup Masa Paskah. Bacaan: Kis 2:1-11; 1 Kor 12:3b-7.12-13; Yoh 20:19-23. Warna liturgi: Merah.'],
            [6, 2, 'Hari Raya Tritunggal Mahakudus',
                'Merayakan misteri Allah Bapa, Putra, dan Roh Kudus. Bacaan: Ams 8:22-31; Rm 5:1-5; Yoh 16:12-15. Warna liturgi: Putih.'],
            [6, 3, 'Hari Raya Tubuh dan Darah Kristus (Corpus Christi)',
                'Merayakan kehadiran nyata Kristus dalam Ekaristi. Prosesi Sakramen Mahakudus. Bacaan: Kej 14:18-20; 1 Kor 11:23-26; Luk 9:11b-17. Warna liturgi: Putih.'],
            [6, 4, 'Minggu Biasa XII',
                'Bacaan: Zak 12:10-11; Gal 3:26-29; Luk 9:18-24. Warna liturgi: Hijau.'],

            // ======== JULI 2025 ========
            [7, 1, 'Minggu Biasa XIII',
                'Bacaan: 1 Raj 19:16b.19-21; Gal 5:1.13-18; Luk 9:51-62. Warna liturgi: Hijau.'],
            [7, 2, 'Minggu Biasa XIV',
                'Bacaan: Yes 66:10-14c; Gal 6:14-18; Luk 10:1-12.17-20. Warna liturgi: Hijau.'],
            [7, 3, 'Minggu Biasa XV',
                'Bacaan: Ul 30:10-14; Kol 1:15-20; Luk 10:25-37. Perumpamaan Orang Samaria yang Baik Hati. Warna liturgi: Hijau.'],
            [7, 4, 'Minggu Biasa XVI',
                'Bacaan: Kej 18:1-10a; Kol 1:24-28; Luk 10:38-42. Maria dan Marta. Warna liturgi: Hijau.'],

            // ======== AGUSTUS 2025 ========
            [8, 1, 'Minggu Biasa XVII',
                'Bacaan: Kej 18:20-32; Kol 2:12-14; Luk 11:1-13. Doa Bapa Kami. Warna liturgi: Hijau.'],
            [8, 2, 'Minggu Biasa XVIII',
                'Bacaan: Pkh 1:2; 2:21-23; Kol 3:1-5.9-11; Luk 12:13-21. Warna liturgi: Hijau.'],
            [8, 3, 'Minggu Biasa XIX',
                'Bacaan: Keb 18:6-9; Ibr 11:1-2.8-19; Luk 12:32-48. Warna liturgi: Hijau.'],
            [8, 4, 'Hari Raya Maria Diangkat ke Surga — Minggu Biasa XX',
                'Hari Raya Bunda Maria Diangkat ke Surga (15 Agustus) merupakan hari raya wajib. Warna liturgi: Putih.'],

            // ======== SEPTEMBER 2025 ========
            [9, 1, 'Minggu Biasa XXI',
                'Bacaan: Yes 66:18-21; Ibr 12:5-7.11-13; Luk 13:22-30. Warna liturgi: Hijau.'],
            [9, 2, 'Minggu Biasa XXII',
                'Bacaan: Sir 3:17-18.20.28-29; Ibr 12:18-19.22-24a; Luk 14:1.7-14. Warna liturgi: Hijau.'],
            [9, 3, 'Minggu Biasa XXIII',
                'Bacaan: Keb 9:13-18b; Flm 9b-10.12-17; Luk 14:25-33. Warna liturgi: Hijau.'],
            [9, 4, 'Minggu Biasa XXIV',
                'Bacaan: Kel 32:7-11.13-14; 1 Tim 1:12-17; Luk 15:1-32. Perumpamaan Anak yang Hilang. Warna liturgi: Hijau.'],

            // ======== OKTOBER 2025 ========
            [10, 1, 'Minggu Biasa XXV — Bulan Rosario',
                'Oktober adalah Bulan Rosario. Umat diajak berdoa Rosario setiap hari. Warna liturgi: Hijau.'],
            [10, 2, 'Minggu Biasa XXVI — Bulan Rosario',
                'Teruslah berdoa Rosario bersama keluarga selama Bulan Oktober. Warna liturgi: Hijau.'],
            [10, 3, 'Minggu Biasa XXVII — Bulan Rosario',
                'Pesta Bunda Maria Ratu Rosario (7 Oktober). Doa Rosario mengikat umat kepada Bunda Maria. Warna liturgi: Hijau.'],
            [10, 4, 'Minggu Biasa XXVIII — Bulan Rosario',
                'Minggu terakhir Bulan Rosario. Renungkan misteri Rosario: Gembira, Cahaya, Sedih, dan Mulia. Warna liturgi: Hijau.'],

            // ======== NOVEMBER 2025 ========
            [11, 1, 'Hari Raya Semua Orang Kudus',
                'Hari Raya Semua Orang Kudus (1 November). Merayakan semua orang yang telah mencapai surga. Warna liturgi: Putih.'],
            [11, 2, 'Peringatan Arwah Semua Orang Beriman',
                'Peringatan Arwah Semua Orang Beriman (2 November). Mendoakan jiwa-jiwa di Api Penyucian. Warna liturgi: Hitam atau Ungu.'],
            [11, 3, 'Minggu Biasa XXXII',
                'Bacaan: 2 Mak 7:1-2.9-14; 2 Tes 2:16–3:5; Luk 20:27-38. Warna liturgi: Hijau.'],
            [11, 4, 'Hari Raya Kristus Raja Semesta Alam',
                'Penutup Tahun Liturgi. Yesus Kristus adalah Raja atas seluruh alam semesta. Bacaan: 2 Sam 5:1-3; Kol 1:12-20; Luk 23:35-43. Warna liturgi: Putih.'],

            // ======== DESEMBER 2025 ========
            [12, 1, 'Minggu Adven I',
                'Awal Tahun Liturgi baru. Masa penantian dan persiapan menyambut Natal. Warna liturgi: Ungu.'],
            [12, 2, 'Minggu Adven II',
                'Siapkan jalan bagi Tuhan, luruskan jalan bagi-Nya (Luk 3:4). Warna liturgi: Ungu.'],
            [12, 3, 'Minggu Adven III (Gaudete)',
                'Minggu Gaudete — Minggu Sukacita. "Bersukacitalah senantiasa dalam Tuhan!" (Fil 4:4). Warna liturgi: Merah Muda (Rose).'],
            [12, 4, 'Minggu Adven IV — Natal Tuhan Yesus Kristus',
                'Minggu Adven IV mempersiapkan kelahiran Tuhan Yesus. 25 Desember: Hari Raya Natal. "Hari ini telah lahir bagimu Juruselamat, yaitu Kristus, Tuhan" (Luk 2:11). Warna liturgi: Ungu, kemudian Putih & Emas.'],
        ];

        $this->command->info('Menyemai data Kalender Liturgi 2025...');
        $count = 0;

        foreach ($data as [$month, $week, $title, $description]) {
            LiturgicalCalendar::updateOrCreate(
                [
                    'year' => 2025,
                    'month' => $month,
                    'week_number' => $week,
                ],
                [
                    'title' => $title,
                    'description' => $description,
                ]
            );
            $count++;
        }

        $this->command->info("✓ Berhasil menyemai {$count} data Kalender Liturgi 2025.");
    }
}