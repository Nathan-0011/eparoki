<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\IntensiMisa;
class IntensiMisaSeeder extends Seeder {
    public function run() {
        $weekDate = now()->startOfWeek();
        IntensiMisa::create(["family_name" => "Keluarga Jonathan Simamora", "amount" => 50000, "description" => "Syukur atas kesehatan", "week_date" => $weekDate]);
        IntensiMisa::create(["family_name" => "Keluarga Marisi Hutapea", "amount" => 100000, "description" => "Mohon kelancaran usaha", "week_date" => $weekDate]);
    }
}