<?php

namespace App\Filament\Widgets;

use App\Models\IntensiMisa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IntensiMisaSummaryWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $aktif = IntensiMisa::where('is_archived', false);
        $countAktif = $aktif->count();
        $totalNominal = $aktif->sum('amount') ?? 0;
        $totalArsip = IntensiMisa::where('is_archived', true)->count();

        return [
            Stat::make('Intensi Minggu Ini', $countAktif)
                ->description('Intensi aktif minggu berjalan')
                ->descriptionIcon('heroicon-o-heart')
                ->color('success'),

            Stat::make('Total Nominal Minggu Ini', 'Rp ' . number_format($totalNominal, 0, ',', '.'))
                ->description('Jumlah persembahan terkumpul')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('warning'),

            Stat::make('Total Arsip', $totalArsip)
                ->description('Total intensi yang telah diarsipkan')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('gray'),
        ];
    }
}
