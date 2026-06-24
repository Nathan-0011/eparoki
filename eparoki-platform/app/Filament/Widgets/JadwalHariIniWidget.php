<?php

namespace App\Filament\Widgets;

use App\Models\JadwalIbadah;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class JadwalHariIniWidget extends BaseWidget
{
    protected static ?string $heading = '📅 Jadwal Ibadah Hari Ini';

    // Tampil di baris kedua, sebelah kiri (setengah lebar)
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'half';

    // Mapping nama hari Inggris ke Indonesia
    private function getNamaHariIndonesia(): string
    {
        $map = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        return $map[now()->englishDayOfWeek] ?? now()->englishDayOfWeek;
    }

    public function table(Table $table): Table
    {
        // Ambil nama hari ini dalam Bahasa Indonesia
        $hariIni = $this->getNamaHariIndonesia();

        return $table
            ->query(
                JadwalIbadah::query()->where('day_of_week', $hariIni)->orderBy('time')
            )
            ->columns([
                Tables\Columns\TextColumn::make('time')
                    ->label('Waktu')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Ibadah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('celebrant')
                    ->label('Selebran')
                    ->placeholder('–'),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->placeholder('–'),
            ])
            ->emptyStateHeading('Tidak Ada Jadwal Hari Ini')
            ->emptyStateDescription('Belum ada jadwal ibadah yang tercatat untuk hari ' . now()->translatedFormat('l, d M Y'))
            ->emptyStateIcon('heroicon-o-calendar')
            ->paginated(false);
    }
}
