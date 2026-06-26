<?php

namespace App\Filament\Resources\IntensiMisaResource\Pages;

use App\Filament\Resources\IntensiMisaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIntensiMisa extends CreateRecord
{
    protected static string $resource = IntensiMisaResource::class;

    protected ?string $heading = 'Tambah Intensi Misa';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}