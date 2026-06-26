<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntensiMisa;
use Carbon\Carbon;

class IntensiMisaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Menyemai data Intensi Misa...');

        $seninMingguIni  = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $seninMingguLalu = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();

        // ─── Minggu Ini (is_archived: false) ───────────────────────────────────
        $dataMingguIni = [
            [
                'family_name' => 'Keluarga Jonathan Simamora',
                'amount'      => 500000,
                'description' => 'Intensi syukur atas diterimanya putra pertama mereka di Kepolisian Republik Indonesia. Semoga senantiasa diberkati dalam pengabdian kepada bangsa dan negara.',
                'week_date'   => $seninMingguIni,
                'is_archived' => false,
            ],
            [
                'family_name' => 'Keluarga Marisi Hutapea',
                'amount'      => 300000,
                'description' => 'Intensi arwah almarhum Bapak Rondang Hutapea yang berpulang 40 hari yang lalu. Semoga beristirahat dalam damai Tuhan.',
                'week_date'   => $seninMingguIni,
                'is_archived' => false,
            ],
            [
                'family_name' => 'Bapak Poltak Situmorang',
                'amount'      => 200000,
                'description' => 'Intensi kesembuhan untuk istri yang sedang sakit dan menjalani perawatan di rumah sakit.',
                'week_date'   => $seninMingguIni,
                'is_archived' => false,
            ],
            [
                'family_name' => 'Keluarga Andreas Tambunan',
                'amount'      => 150000,
                'description' => 'Intensi syukur atas kelulusan putri mereka dari Universitas Sumatera Utara dengan nilai terbaik.',
                'week_date'   => $seninMingguIni,
                'is_archived' => false,
            ],
            [
                'family_name' => 'Ibu Theresia Napitupulu',
                'amount'      => null,
                'description' => 'Intensi khusus untuk kelancaran perjalanan suami yang bekerja di luar kota selama sebulan.',
                'week_date'   => $seninMingguIni,
                'is_archived' => false,
            ],
            [
                'family_name' => 'Keluarga Benediktus Sinaga',
                'amount'      => 250000,
                'description' => 'Intensi ulang tahun pernikahan ke-25. Syukur atas berkat kesetiaan selama 25 tahun bersama.',
                'week_date'   => $seninMingguIni,
                'is_archived' => false,
            ],
        ];

        // ─── Minggu Lalu (is_archived: true) ───────────────────────────────────
        $dataMingguLalu = [
            [
                'family_name' => 'Keluarga Rosmawati Siahaan',
                'amount'      => 400000,
                'description' => 'Intensi arwah 1000 hari almarhum Bapak Darius Siahaan.',
                'week_date'   => $seninMingguLalu,
                'is_archived' => true,
            ],
            [
                'family_name' => 'Keluarga Petrus Manullang',
                'amount'      => 175000,
                'description' => 'Intensi syukur atas kehamilan anak pertama.',
                'week_date'   => $seninMingguLalu,
                'is_archived' => true,
            ],
            [
                'family_name' => 'Ibu Maria Sirait',
                'amount'      => null,
                'description' => 'Intensi khusus untuk kelulusan ujian sertifikasi suami.',
                'week_date'   => $seninMingguLalu,
                'is_archived' => true,
            ],
        ];

        $allData = array_merge($dataMingguIni, $dataMingguLalu);
        $count = 0;

        foreach ($allData as $row) {
            IntensiMisa::updateOrCreate(
                [
                    'family_name' => $row['family_name'],
                    'week_date'   => $row['week_date'],
                ],
                $row
            );
            $count++;
        }

        $this->command->info("✓ Berhasil menyemai {$count} data Intensi Misa.");
    }
}