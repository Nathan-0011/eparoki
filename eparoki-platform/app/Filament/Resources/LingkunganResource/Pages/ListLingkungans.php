<?php

namespace App\Filament\Resources\LingkunganResource\Pages;

use App\Filament\Resources\LingkunganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLingkungans extends ListRecords
{
    protected static string $resource = LingkunganResource::class;

    protected ?string $heading = 'Daftar Lingkungan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Lingkungan')->icon('heroicon-o-plus'),
        ];
    }
}