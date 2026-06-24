<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParishProfile;

class ParishProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Method getProfile() di Model sudah otomatis menghandle logika 
        // firstOrCreate beserta data defaultnya. Jadi kita cukup memanggilnya.
        ParishProfile::getProfile();
    }
}
