<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pastor;
use App\Models\BphMember;

class PastorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Menyemai data Pastor dan Anggota BPH...');

        // Pastor 1
        Pastor::updateOrCreate(
            ['name' => 'Rm. Antonius Gultom, OFMCap'],
            [
                'period_start' => 2008,
                'period_end' => 2014,
                'is_active' => false,
                'biography' => '<p>Pastor kelahiran Samosir yang memimpin paroki selama 6 tahun. Di bawah kepemimpinannya, gereja Santo Fidelis mengalami pertumbuhan umat yang signifikan dan pembangunan aula paroki.</p>',
            ]
        );

        // Pastor 2
        Pastor::updateOrCreate(
            ['name' => 'Rm. Benediktus Manurung, Pr'],
            [
                'period_start' => 2014,
                'period_end' => 2020,
                'is_active' => false,
                'biography' => '<p>Imam praja Keuskupan Agung Medan. Memimpin paroki selama 6 tahun dengan program unggulan pembinaan iman keluarga dan pengembangan komunitas lingkungan.</p>',
            ]
        );

        // Pastor 3 (Aktif)
        $pastorAktif = Pastor::updateOrCreate(
            ['name' => 'Rm. Fidelis Situmeang, OFMCap'],
            [
                'period_start' => 2020,
                'period_end' => null,
                'is_active' => true,
                'biography' => '<p>Pastor aktif paroki saat ini. Berfokus pada digitalisasi pelayanan pastoral dan penguatan komunitas umat basis di setiap lingkungan paroki.</p>',
            ]
        );

        // BPH untuk Pastor Aktif
        $bphData = [
            [
                'name' => 'Rm. Fidelis Situmeang, OFMCap',
                'position' => 'Pastor Paroki',
                'period_start' => 2020,
                'period_end' => null,
            ],
            [
                'name' => 'Diakon Marihot Siahaan',
                'position' => 'Wakil Pastor',
                'period_start' => 2020,
                'period_end' => null,
            ],
            [
                'name' => 'Bapak Poltak Situmorang',
                'position' => 'Ketua Dewan Pastoral',
                'period_start' => 2020,
                'period_end' => null,
            ],
            [
                'name' => 'Ibu Rosmawati Hutapea',
                'position' => 'Sekretaris',
                'period_start' => 2020,
                'period_end' => null,
            ],
            [
                'name' => 'Bapak Jonatan Sinaga',
                'position' => 'Bendahara I',
                'period_start' => 2020,
                'period_end' => null,
            ],
            [
                'name' => 'Ibu Maria Sirait',
                'position' => 'Bendahara II',
                'period_start' => 2020,
                'period_end' => null,
            ],
            [
                'name' => 'Bapak Andreas Tambunan',
                'position' => 'Koordinator Liturgi',
                'period_start' => 2020,
                'period_end' => null,
            ],
            [
                'name' => 'Ibu Theresia Napitupulu',
                'position' => 'Koordinator Sosial',
                'period_start' => 2020,
                'period_end' => null,
            ],
        ];

        foreach ($bphData as $data) {
            BphMember::updateOrCreate(
                [
                    'pastor_id' => $pastorAktif->id,
                    'name' => $data['name'],
                    'position' => $data['position'],
                ],
                [
                    'period_start' => $data['period_start'],
                    'period_end' => $data['period_end'],
                ]
            );
        }

        $this->command->info('✓ Berhasil menyemai data Pastor dan BPH.');
    }
}