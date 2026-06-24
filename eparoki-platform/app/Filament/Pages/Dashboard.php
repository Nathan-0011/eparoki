<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\JadwalHariIniWidget;
use App\Filament\Widgets\IntensiMisaWidget;
use App\Filament\Widgets\InfoParokiWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = -1; // Paling atas di sidebar

    // Daftarkan hanya widget buatan kita (hapus widget bawaan Filament)
    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            JadwalHariIniWidget::class,
            IntensiMisaWidget::class,
            InfoParokiWidget::class,
        ];
    }

    // Jumlah kolom grid default = 2 (untuk widget "half")
    public function getColumns(): int | string | array
    {
        return 2;
    }
}
