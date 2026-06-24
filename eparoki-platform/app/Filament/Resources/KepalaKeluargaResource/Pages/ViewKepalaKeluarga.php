<?php

namespace App\Filament\Resources\KepalaKeluargaResource\Pages;

use App\Filament\Resources\KepalaKeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class ViewKepalaKeluarga extends ViewRecord
{
    protected static string $resource = KepalaKeluargaResource::class;

    protected ?string $heading = 'Detail Keluarga';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Ubah Data'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Data Keluarga')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('nomor_kk')->label('Nomor KK')->placeholder('-'),
                            TextEntry::make('lingkungan.name')->label('Lingkungan')->badge()->color('info'),
                            TextEntry::make('nama_kk')->label('Kepala Keluarga')->weight('bold'),
                            TextEntry::make('nama_pasangan')->label('Pasangan')->placeholder('-'),
                            TextEntry::make('no_telp')->label('Nomor Telepon')->placeholder('-'),
                            IconEntry::make('is_active')->label('Status Aktif')->boolean(),
                        ]),
                        TextEntry::make('alamat')->label('Alamat Lengkap')->placeholder('-'),
                    ]),

                Section::make('Data Gerejawi')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('tanggal_bergabung')->label('Tanggal Bergabung')->date('d M Y')->placeholder('-'),
                            TextEntry::make('status_umat')->label('Status Umat')->badge()
                                ->color(fn ($state) => match ($state) {
                                    'Aktif' => 'success',
                                    'Pindah Paroki' => 'warning',
                                    'Meninggal Dunia' => 'danger',
                                    default => 'gray',
                                }),
                        ]),
                        TextEntry::make('keterangan')->label('Keterangan / Catatan')->placeholder('-'),
                    ]),
            ]);
    }
}
