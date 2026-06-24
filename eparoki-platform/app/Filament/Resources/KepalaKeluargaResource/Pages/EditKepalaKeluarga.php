<?php

namespace App\Filament\Resources\KepalaKeluargaResource\Pages;

use App\Filament\Resources\KepalaKeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKepalaKeluarga extends EditRecord
{
    protected static string $resource = KepalaKeluargaResource::class;

    protected ?string $heading = 'Ubah Data Kepala Keluarga';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('Detail'),
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}