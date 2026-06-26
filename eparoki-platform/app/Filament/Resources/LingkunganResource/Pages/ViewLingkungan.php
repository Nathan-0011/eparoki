<?php

namespace App\Filament\Resources\LingkunganResource\Pages;

use App\Filament\Resources\LingkunganResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewLingkungan extends ViewRecord
{
    protected static string $resource = LingkunganResource::class;

    protected ?string $heading = 'Detail Lingkungan';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Info Lingkungan')
                    ->schema([
                        ImageEntry::make('photo')
                            ->hiddenLabel()
                            ->circular(false)
                            ->extraImgAttributes(['style' => 'max-height: 150px; border-radius: 8px; object-fit: cover;'])
                            ->defaultImageUrl(null),

                        TextEntry::make('name')
                            ->label('Nama Lingkungan')
                            ->weight('bold')
                            ->size(TextEntry\TextEntrySize::Large),

                        TextEntry::make('patron_saint')
                            ->label('Santo Pelindung')
                            ->color('gray'),

                        TextEntry::make('jumlah_kk')
                            ->label('Total KK')
                            ->getStateUsing(fn ($record) => $record->kepalaKeluarga()->count() . ' KK'),

                        TextEntry::make('jumlah_anggota')
                            ->label('Total Jiwa')
                            ->getStateUsing(fn ($record) => $record->kepalaKeluarga()->sum('jumlah_anggota') . ' Jiwa'),
                    ])->columns(2),
            ]);
    }
}
