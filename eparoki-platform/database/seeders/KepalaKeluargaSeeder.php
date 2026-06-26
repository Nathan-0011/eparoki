<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lingkungan;
use App\Models\KepalaKeluarga;

class KepalaKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Menyemai data Kepala Keluarga...');

        $lingkungans = Lingkungan::all();
        if ($lingkungans->isEmpty()) {
            $this->command->error('Tabel lingkungan kosong. Jalankan LingkunganSeeder terlebih dahulu.');
            return;
        }

        $margas = [
            'Siahaan', 'Situmorang', 'Simamora', 'Hutapea', 
            'Manullang', 'Sinaga', 'Tambunan', 'Sirait', 
            'Napitupulu', 'Pangaribuan', 'Tobing', 'Silalahi',
            'Nainggolan', 'Pakpahan', 'Lumbantobing',
            'Siburian', 'Manurung', 'Pardosi'
        ];

        $alamatPrefix = ['Jl. Sisingamangaraja No.', 'Jl. Haranggaol No.', 'Jl. TPR Sinaga No.', 'Jl. Josep No.', 'Komp. Gereja No.'];

        foreach ($lingkungans as $lingkungan) {
            for ($i = 1; $i <= 8; $i++) {
                $marga = $margas[array_rand($margas)];
                $namaKk = "Keluarga {$marga}";
                $alamat = $alamatPrefix[array_rand($alamatPrefix)] . rand(1, 99) . ', Parapat';
                $anggota = rand(2, 6);
                $telp = '0812' . rand(10000000, 99999999);

                KepalaKeluarga::updateOrCreate(
                    [
                        'nama_kk' => $namaKk,
                        'lingkungan_id' => $lingkungan->id,
                    ],
                    [
                        'alamat' => $alamat,
                        'jumlah_anggota' => $anggota,
                        'no_telp' => rand(0, 1) ? $telp : null,
                    ]
                );
            }
        }

        $this->command->info('✓ Berhasil menyemai 80 data Kepala Keluarga.');
    }
}