<?php

namespace App\Filament\Resources\JadwalIbadahResource\Pages;

use App\Filament\Resources\JadwalIbadahResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

class ViewJadwalIbadah extends ViewRecord
{
    protected static string $resource = JadwalIbadahResource::class;

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
                Section::make('Identitas Ibadah')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('nama_ibadah')->label('Nama Ibadah')->weight('bold'),
                            TextEntry::make('jenis_ibadah')->label('Jenis Ibadah')->badge()->color('primary'),
                        ]),
                    ]),

                Section::make('Waktu Pelaksanaan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('day_of_week')->label('Hari')->badge()
                                ->color(fn ($state) => match ($state) {
                                    'Minggu' => 'danger', 'Sabtu' => 'warning', default => 'primary',
                                }),
                            TextEntry::make('time')->label('Jam Mulai')
                                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : '–'),
                            TextEntry::make('jam_selesai')->label('Jam Selesai')
                                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : '–')
                                ->placeholder('–'),
                        ]),
                        TextEntry::make('tanggal')->label('Tanggal Spesifik')
                            ->date('d M Y')->placeholder('Tidak ada tanggal khusus'),
                    ]),

                Section::make('Lokasi & Pemimpin')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('location')->label('Lokasi'),
                            TextEntry::make('celebrant')->label('Pemimpin Ibadah')->placeholder('–'),
                        ]),
                    ]),

                Section::make('Keterangan & Status')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('status')->label('Status')->badge()
                                ->color(fn ($state) => match ($state) {
                                    'Aktif' => 'success', 'Selesai' => 'gray', 'Dibatalkan' => 'danger', default => 'gray',
                                }),
                            TextEntry::make('keterangan')->label('Keterangan')->placeholder('Tidak ada keterangan.'),
                        ]),
                    ]),
            ]);
    }
}
