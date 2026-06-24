<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
            LingkunganSeeder::class,
            KepalaKeluargaSeeder::class,
            PastorSeeder::class,
            LiturgicalCalendarSeeder::class,
            JadwalIbadahSeeder::class,
            BannerSeeder::class,
            IntensiMisaSeeder::class,
            ParishProfileSeeder::class
        ]);
    }
}