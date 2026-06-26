<?php

namespace App\Filament\Widgets;

use App\Models\JadwalIbadah;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class JadwalHariIniWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 2;
    
    protected function getTableHeading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        Carbon::setLocale('id');
        $date = Carbon::now()->translatedFormat('l, d F Y');
        return "📅 Jadwal Ibadah Hari Ini ({$date})";
    }

    public function table(Table $table): Table
    {
        $today = Carbon::today()->toDateString();
        
        return $table
            ->query(
                JadwalIbadah::query()
                    ->where('tanggal', $today)
                    ->orderBy('time', 'asc')
                    ->limit(10)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('time')
                    ->label('Pukul')
                    ->time('H:i')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('H:i') . ' WIB')
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('jenis_ibadah')
                    ->label('Jenis Ibadah')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Misa Mingguan' => 'primary',
                        'Misa Harian' => 'success',
                        'Pernikahan' => 'warning',
                        'Pemberkatan' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('celebrant')
                    ->label('Selebran')
                    ->default('-'),
            ])
            ->emptyStateHeading('Tidak Ada Jadwal Hari Ini')
            ->emptyStateDescription('Belum ada jadwal ibadah yang tercatat untuk hari ini.')
            ->emptyStateIcon('heroicon-o-calendar');
    }
}
