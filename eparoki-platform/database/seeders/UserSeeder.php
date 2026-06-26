<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Membuat 2 akun akses admin...');

        User::updateOrCreate(
            ['email' => 'admin@ekatolik.com'],
            [
                'name' => 'Admin Paroki',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@ekatolik.com'],
            [
                'name' => 'Operator Sekretariat',
                'password' => Hash::make('password123'),
            ]
        );

        $this->command->info('✓ Berhasil membuat akun Admin dan Operator.');
    }
}