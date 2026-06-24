<?php

namespace App\Filament\Widgets;

use App\Models\IntensiMisa;
use App\Models\JadwalIbadah;
use App\Models\KepalaKeluarga;
use App\Models\Lingkungan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    // Urutan tampil di dashboard: paling atas
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Hitung data dari database secara real-time
        $totalKK = KepalaKeluarga::count();
        $totalLingkungan = Lingkungan::count();
        $intensiMingguIni = IntensiMisa::currentWeek()->count();

        // Jadwal hari ini — hari disimpan dalam Bahasa Indonesia di database
        $hariMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $hariIni = $hariMap[now()->englishDayOfWeek] ?? now()->englishDayOfWeek;
        $jadwalHariIni = JadwalIbadah::where('day_of_week', $hariIni)->count();

        return [
            Stat::make('Total Kepala Keluarga', number_format($totalKK))
                ->description('Terdaftar di semua lingkungan')
                ->descriptionIcon('heroicon-m-home')
                ->color('info'),

            Stat::make('Total Lingkungan', number_format($totalLingkungan))
                ->description('Wilayah dalam paroki')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),

            Stat::make('Intensi Misa Aktif', number_format($intensiMingguIni))
                ->description('Minggu ini (belum diarsipkan)')
                ->descriptionIcon('heroicon-m-heart')
                ->color('primary'),

            Stat::make('Jadwal Ibadah Hari Ini', number_format($jadwalHariIni))
                ->description('Kebaktian ' . now()->translatedFormat('l, d M Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
        ];
    }
}
