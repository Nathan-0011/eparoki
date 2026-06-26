<?php

namespace App\Filament\Resources\BphMemberResource\Pages;

use App\Filament\Resources\BphMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBphMembers extends ListRecords
{
    protected static string $resource = BphMemberResource::class;

    protected ?string $heading = 'Daftar Anggota BPH';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Anggota BPH')
                ->icon('heroicon-o-plus'),
        ];
    }
}