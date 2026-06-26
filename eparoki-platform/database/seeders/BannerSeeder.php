<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use Carbon\Carbon;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Menyemai data Banner Kegiatan...');

        $banners = [
            // Banner 1 — Aktif sekarang
            [
                'title' => 'Selamat Hari Raya Paskah 2025',
                'image_path' => 'banners/paskah-2025.jpg',
                'is_active' => true,
                'start_date' => null,
                'end_date' => null,
                'order' => 1,
            ],
            // Banner 2 — Aktif sekarang
            [
                'title' => 'Misa Agung Pesta Santo Fidelis — 24 April',
                'image_path' => 'banners/pesta-santo-fidelis.jpg',
                'is_active' => true,
                'start_date' => Carbon::now()->subDays(7)->toDateString(),
                'end_date' => Carbon::now()->addDays(14)->toDateString(),
                'order' => 2,
            ],
            // Banner 3 — Aktif sekarang
            [
                'title' => 'Retret Keluarga Paroki — Juni 2025',
                'image_path' => 'banners/retret-keluarga.jpg',
                'is_active' => true,
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addMonth()->toDateString(),
                'order' => 3,
            ],
            // Banner 4 — Nonaktif (contoh arsip)
            [
                'title' => 'Natal 2024 — Selamat Hari Natal',
                'image_path' => 'banners/natal-2024.jpg',
                'is_active' => false,
                'start_date' => null,
                'end_date' => null,
                'order' => 4,
            ],
        ];

        $count = 0;
        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
            $count++;
        }

        $this->command->info("✓ Berhasil menyemai {$count} data Banner Kegiatan.");
    }
}