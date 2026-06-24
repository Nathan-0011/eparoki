<?php
namespace App\Filament\Widgets;
use App\Models\SongNumber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LatestSongWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $latest = SongNumber::latest()->first();
        return [
            Stat::make("Nomor Lagu Terakhir", $latest ? $latest->song_number : "Belum Ada")
                ->description("Status: " . ($latest ? $latest->status : "-"))
                ->color("success")
                ->icon("heroicon-o-musical-note"),
        ];
    }
}
