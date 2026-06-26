<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lingkungan;

class LingkunganSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Menyemai data Lingkungan...');

        $lingkungans = [
            [
                'name' => 'Lingkungan Santo Antonius',
                'patron_saint' => 'Santo Antonius dari Padua',
                'description' => 'Mencakup wilayah pusat kota Parapat dan sekitar Jalan Sisingamangaraja',
            ],
            [
                'name' => 'Lingkungan Santo Petrus',
                'patron_saint' => 'Santo Petrus Rasul',
                'description' => 'Mencakup wilayah Pelabuhan Parapat dan Jalan Haranggaol',
            ],
            [
                'name' => 'Lingkungan Santa Maria',
                'patron_saint' => 'Santa Maria Bunda Allah',
                'description' => 'Mencakup wilayah Tigaras dan sekitarnya',
            ],
            [
                'name' => 'Lingkungan Santo Yohanes',
                'patron_saint' => 'Santo Yohanes Rasul',
                'description' => 'Mencakup wilayah Ajibata dan Tomok',
            ],
            [
                'name' => 'Lingkungan Santo Paulus',
                'patron_saint' => 'Santo Paulus Rasul',
                'description' => 'Mencakup wilayah Meat dan Parbaba',
            ],
            [
                'name' => 'Lingkungan Santo Mikhael',
                'patron_saint' => 'Santo Mikhael Malaikat Agung',
                'description' => 'Mencakup wilayah Simanindo dan Tuk-Tuk',
            ],
            [
                'name' => 'Lingkungan Santo Fransiskus',
                'patron_saint' => 'Santo Fransiskus Asisi',
                'description' => 'Mencakup wilayah Ambarita dan Siallagan',
            ],
            [
                'name' => 'Lingkungan Santo Yosep',
                'patron_saint' => 'Santo Yosep Pelindung Keluarga',
                'description' => 'Mencakup wilayah Nainggolan dan Onan Runggu',
            ],
            [
                'name' => 'Lingkungan Santa Theresia',
                'patron_saint' => 'Santa Theresia dari Lisieux',
                'description' => 'Mencakup wilayah Pangururan dan Ronggur Nihuta',
            ],
            [
                'name' => 'Lingkungan Santo Dominikus',
                'patron_saint' => 'Santo Dominikus de Guzman',
                'description' => 'Mencakup wilayah Mogang dan Palipi',
            ],
        ];

        foreach ($lingkungans as $data) {
            Lingkungan::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('✓ Berhasil menyemai 10 data Lingkungan.');
    }
}