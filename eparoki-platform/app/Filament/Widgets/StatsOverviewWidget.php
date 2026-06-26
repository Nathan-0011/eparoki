<?php

namespace App\Filament\Widgets;

use App\Models\KepalaKeluarga;
use App\Models\Lingkungan;
use App\Models\IntensiMisa;
use App\Models\SongNumber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    // Supaya widget bisa auto-refresh jika data berubah (opsional)
    protected static ?string $pollingInterval = '30s';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $kkCount = KepalaKeluarga::count();
        $lingkCount = Lingkungan::count();
        
        $intensiMingguIni = IntensiMisa::where('is_archived', false);
        $intensiCount = $intensiMingguIni->count();
        $intensiTotal = $intensiMingguIni->sum('amount') ?? 0;
        
        $latestSong = SongNumber::latest('sent_at')->first();

        return [
            Stat::make('Kepala Keluarga', $kkCount)
                ->description("dalam {$lingkCount} lingkungan")
                ->descriptionIcon('heroicon-o-users')
                ->color('info'), // biru (pakai info)

            Stat::make('Lingkungan', $lingkCount)
                ->description('dalam Paroki Santo Fidelis')
                ->descriptionIcon('heroicon-o-home')
                ->color('success'), // hijau (pakai success)

            Stat::make('Intensi Misa', $intensiCount)
                ->description($intensiTotal > 0 ? "Total: Rp " . number_format($intensiTotal, 0, ',', '.') : 'Belum ada intensi')
                ->descriptionIcon('heroicon-o-heart')
                ->color('primary'), // ungu/primary (pakai primary)

            Stat::make('Nomor Lagu Terakhir', $latestSong?->song_number ?? '-')
                ->description($latestSong ? "Dikirim " . $latestSong->sent_at?->diffForHumans() : 'Belum ada pengiriman')
                ->descriptionIcon('heroicon-o-musical-note')
                ->color('warning'), // oranye (pakai warning)
        ];
    }
}
