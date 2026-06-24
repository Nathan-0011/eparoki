<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\KepalaKeluarga;
use App\Models\Lingkungan;
class KepalaKeluargaSeeder extends Seeder {
    public function run() {
        $lingkungans = Lingkungan::all();
        $margas = ["Siahaan", "Situmorang", "Simamora", "Hutapea", "Nainggolan", "Sinaga", "Silalahi", "Manurung", "Pasaribu", "Sitorus"];
        $namas = ["Marihot", "Poltak", "Budi", "Johannes", "Parlindungan", "Sahat", "Togu", "Binsar", "Halomoan", "Togar"];
        
        foreach($lingkungans as $ling) {
            for($i=0; $i<10; $i++) {
                KepalaKeluarga::create([
                    "lingkungan_id" => $ling->id,
                    "nama_kk" => "Keluarga " . $namas[array_rand($namas)] . " " . $margas[array_rand($margas)],
                    "jumlah_anggota" => rand(2, 6)
                ]);
            }
        }
    }
}