<?php

namespace App\Filament\Resources\BphMemberResource\Pages;

use App\Filament\Resources\BphMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBphMember extends EditRecord
{
    protected static string $resource = BphMemberResource::class;

    protected ?string $heading = 'Ubah Data Anggota BPH';

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