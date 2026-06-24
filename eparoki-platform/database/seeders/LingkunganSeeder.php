<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Lingkungan;
class LingkunganSeeder extends Seeder {
    public function run() {
        $santos = ["Santo Antonius", "Santo Petrus", "Santa Maria", "Santo Yosef", "Santo Mikael", "Santo Yohanes", "Santo Paulus", "Santa Teresia", "Santo Fransiskus", "Santo Stefanus"];
        foreach($santos as $s) {
            Lingkungan::create(["name" => "Lingkungan ".$s, "patron_saint" => $s]);
        }
    }
}