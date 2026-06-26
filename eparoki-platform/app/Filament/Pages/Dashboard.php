<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\JadwalHariIniWidget;
use App\Filament\Widgets\IntensiMisaWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = -1; // Paling atas di sidebar

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            JadwalHariIniWidget::class,
            IntensiMisaWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
            'xl' => 4,
        ];
    }
}
