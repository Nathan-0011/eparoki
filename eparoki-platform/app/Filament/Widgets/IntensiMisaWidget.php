<?php

namespace App\Filament\Widgets;

use App\Models\IntensiMisa;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class IntensiMisaWidget extends BaseWidget
{
    protected static ?string $heading = '🙏 Intensi Misa Minggu Ini';

    // Tampil di baris kedua, sebelah kanan (setengah lebar)
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'half';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // currentWeek() = is_archived = false
                IntensiMisa::query()->currentWeek()->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('family_name')
                    ->label('Nama Keluarga')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => $state
                        ? 'Rp ' . number_format($state, 0, ',', '.')
                        : '–'
                    )
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap()
                    ->placeholder('–'),
            ])
            ->emptyStateHeading('Belum Ada Intensi Misa')
            ->emptyStateDescription('Belum ada intensi misa yang tercatat untuk minggu ini.')
            ->emptyStateIcon('heroicon-o-heart')
            ->paginated(5);
    }
}
