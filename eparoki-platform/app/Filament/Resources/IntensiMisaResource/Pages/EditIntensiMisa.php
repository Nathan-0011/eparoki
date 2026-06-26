<?php

namespace App\Filament\Resources\IntensiMisaResource\Pages;

use App\Filament\Resources\IntensiMisaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntensiMisa extends EditRecord
{
    protected static string $resource = IntensiMisaResource::class;

    protected ?string $heading = 'Ubah Data Intensi Misa';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}