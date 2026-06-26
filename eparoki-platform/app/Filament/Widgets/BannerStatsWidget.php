<?php

namespace App\Filament\Widgets;

use App\Models\Banner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class BannerStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $activeCount = Banner::active()->count();
        
        $scheduledCount = Banner::where('is_active', true)
            ->where('start_date', '>', Carbon::now()->toDateString())
            ->count();
            
        $totalCount = Banner::count();

        return [
            Stat::make('Banner Aktif', $activeCount)
                ->description('Tampil di aplikasi sekarang')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Terjadwal', $scheduledCount)
                ->description('Akan tampil mendatang')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Total Banner', $totalCount)
                ->description('Semua banner yang terdaftar')
                ->descriptionIcon('heroicon-o-photo')
                ->color('gray'),
        ];
    }
}
