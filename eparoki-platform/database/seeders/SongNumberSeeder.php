<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SongNumber;
use Carbon\Carbon;

class SongNumberSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Menyemai data Nomor Lagu (Riwayat)...');

        $songs = [
            ['song_number' => '0005', 'status' => 'sent', 'time_offset' => 60],
            ['song_number' => '0023', 'status' => 'sent', 'time_offset' => 50],
            ['song_number' => '0145', 'status' => 'failed', 'time_offset' => 45],
            ['song_number' => '0287', 'status' => 'sent', 'time_offset' => 30],
            ['song_number' => '0312', 'status' => 'sent', 'time_offset' => 20],
            ['song_number' => '0456', 'status' => 'failed', 'time_offset' => 15],
            ['song_number' => '0523', 'status' => 'sent', 'time_offset' => 10],
            ['song_number' => '0678', 'status' => 'pending', 'time_offset' => 5],
            ['song_number' => '0712', 'status' => 'sent', 'time_offset' => 2],
            ['song_number' => '2100', 'status' => 'sent', 'time_offset' => 0],
        ];

        foreach ($songs as $song) {
            SongNumber::create([
                'song_number' => $song['song_number'],
                'device_id'   => 'display-01',
                'status'      => $song['status'],
                'sent_at'     => Carbon::now()->subMinutes($song['time_offset']),
            ]);
        }

        $this->command->info('✓ Berhasil menyemai 10 data riwayat Nomor Lagu.');
    }
}
