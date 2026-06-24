<?php

namespace App\Filament\Resources\LiturgicalCalendarResource\Pages;

use App\Filament\Resources\LiturgicalCalendarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLiturgicalCalendars extends ListRecords
{
    protected static string $resource = LiturgicalCalendarResource::class;

    protected ?string $heading = 'Daftar Kalender Liturgi';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kalender')
                ->icon('heroicon-o-plus'),
        ];
    }
}