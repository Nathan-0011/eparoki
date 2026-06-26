<?php

namespace App\Filament\Resources\SongNumberResource\Pages;

use App\Filament\Resources\SongNumberResource;
use App\Filament\Widgets\LatestSongNumberWidget;
use App\Models\SongNumber;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListSongNumbers extends ListRecords
{
    protected static string $resource = SongNumberResource::class;

    protected ?string $heading = 'Riwayat Nomor Lagu';

    protected function getHeaderWidgets(): array
    {
        return [
            LatestSongNumberWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Kirim Nomor Lagu')
                ->icon('heroicon-o-musical-note'),
                
            Actions\Action::make('truncate')
                ->label('Hapus Riwayat')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus semua riwayat nomor lagu?')
                ->modalDescription('Tindakan ini akan menghapus permanen seluruh riwayat pengiriman nomor lagu dan tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus Semua')
                ->action(function () {
                    SongNumber::truncate();
                    Notification::make()->title('Riwayat berhasil dihapus')->success()->send();
                }),
        ];
    }
}