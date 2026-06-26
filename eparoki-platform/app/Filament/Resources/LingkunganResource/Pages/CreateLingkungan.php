<?php

namespace App\Filament\Resources\LingkunganResource\Pages;

use App\Filament\Resources\LingkunganResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLingkungan extends CreateRecord
{
    protected static string $resource = LingkunganResource::class;

    protected ?string $heading = 'Tambah Lingkungan';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}