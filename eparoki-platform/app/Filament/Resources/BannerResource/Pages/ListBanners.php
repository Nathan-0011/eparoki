<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use App\Filament\Widgets\BannerStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    protected ?string $heading = 'Daftar Banner Kegiatan';

    protected function getHeaderWidgets(): array
    {
        return [
            BannerStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Banner')
                ->icon('heroicon-o-plus'),
        ];
    }
}