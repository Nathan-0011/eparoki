<?php

namespace App\Filament\Resources\PastorResource\Pages;

use App\Filament\Resources\PastorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPastors extends ListRecords
{
    protected static string $resource = PastorResource::class;

    protected ?string $heading = 'Daftar Pastor Paroki';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pastor')
                ->icon('heroicon-o-plus'),
        ];
    }
}