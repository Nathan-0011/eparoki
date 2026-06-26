<?php

namespace App\Filament\Resources\PastorResource\Pages;

use App\Filament\Resources\PastorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePastor extends CreateRecord
{
    protected static string $resource = PastorResource::class;

    protected ?string $heading = 'Tambah Pastor Paroki';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}