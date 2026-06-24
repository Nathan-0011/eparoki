<?php
namespace App\Filament\Widgets;
use App\Models\Banner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BannerAktif extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Banner::active()->count();
        return [
            Stat::make("Banner Aktif", $count)
                ->color($count > 0 ? "success" : "danger")
                ->icon("heroicon-o-photo"),
        ];
    }
}
