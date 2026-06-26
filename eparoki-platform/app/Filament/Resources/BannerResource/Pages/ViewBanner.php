<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewBanner extends ViewRecord
{
    protected static string $resource = BannerResource::class;
    
    protected ?string $heading = 'Detail Banner Kegiatan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Ubah Banner'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Preview Gambar')
                    ->schema([
                        ImageEntry::make('image_path')
                            ->hiddenLabel()
                            ->width('100%')
                            ->extraImgAttributes(['style' => 'object-fit:cover; border-radius:12px; max-height:400px;']),
                    ]),
                    
                Section::make('Informasi Banner')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('title')
                                ->label('Judul Banner')
                                ->weight('bold')
                                ->size(TextEntry\TextEntrySize::Large),
                                
                            TextEntry::make('status')
                                ->label('Status Saat Ini')
                                ->badge()
                                ->state(fn ($record) => $record->status_label)
                                ->color(fn (string $state): string => match ($state) {
                                    'Aktif' => 'success',
                                    'Terjadwal' => 'warning',
                                    'Nonaktif' => 'danger',
                                    'Kedaluwarsa' => 'gray',
                                    default => 'gray',
                                }),
                                
                            TextEntry::make('periode')
                                ->label('Periode Tampil')
                                ->state(function ($record) {
                                    $start = $record->start_date ? $record->start_date->translatedFormat('d F Y') : 'Sekarang';
                                    $end = $record->end_date ? $record->end_date->translatedFormat('d F Y') : 'Tidak terbatas';
                                    return "$start — $end";
                                }),
                                
                            TextEntry::make('order')
                                ->label('Urutan Carousel')
                                ->badge()
                                ->color('gray'),
                        ]),
                    ]),
            ]);
    }
}
