<?php

namespace App\Filament\Widgets;

use App\Models\IntensiMisa;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class IntensiMisaWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 2;
    
    protected function getTableHeading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return '🙏 Intensi Misa Minggu Ini';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                IntensiMisa::query()
                    ->where('is_archived', false)
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('family_name')
                    ->label('Nama Keluarga')
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-')
                    ->default('-'),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description),
            ])
            ->emptyStateHeading('Belum Ada Intensi')
            ->emptyStateDescription('Belum ada intensi misa minggu ini.')
            ->emptyStateIcon('heroicon-o-heart');
    }
}
