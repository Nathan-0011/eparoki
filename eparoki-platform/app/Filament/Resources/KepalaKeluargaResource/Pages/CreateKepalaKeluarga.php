<?php

namespace App\Filament\Resources\KepalaKeluargaResource\Pages;

use App\Filament\Resources\KepalaKeluargaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKepalaKeluarga extends CreateRecord
{
    protected static string $resource = KepalaKeluargaResource::class;

    protected ?string $heading = 'Tambah Kepala Keluarga';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}