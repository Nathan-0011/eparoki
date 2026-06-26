<?php

namespace App\Filament\Resources\BphMemberResource\Pages;

use App\Filament\Resources\BphMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBphMember extends CreateRecord
{
    protected static string $resource = BphMemberResource::class;

    protected ?string $heading = 'Tambah Anggota BPH';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}