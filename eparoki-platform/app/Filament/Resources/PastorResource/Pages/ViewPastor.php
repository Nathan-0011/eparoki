<?php

namespace App\Filament\Resources\PastorResource\Pages;

use App\Filament\Resources\PastorResource;
use App\Filament\Resources\BphMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;

class ViewPastor extends ViewRecord
{
    protected static string $resource = PastorResource::class;
    
    protected ?string $heading = 'Detail Pastor Paroki';

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
                Section::make('Identitas & Masa Bakti')
                    ->schema([
                        Grid::make(3)->schema([
                            ImageEntry::make('photo')
                                ->label('')
                                ->hiddenLabel()
                                ->circular()
                                ->size(120)
                                ->defaultImageUrl(url('/images/default-avatar.png'))
                                ->columnSpan(1),
                            
                            Grid::make(1)->schema([
                                TextEntry::make('name')
                                    ->label('Nama Pastor')
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->weight('bold'),
                                
                                TextEntry::make('periode')
                                    ->label('Masa Bertugas')
                                    ->formatStateUsing(function ($record) {
                                        return "{$record->period_start} — " . ($record->period_end ?? 'Sekarang');
                                    }),
                                    
                                TextEntry::make('is_active')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn ($state) => $state ? 'success' : 'gray')
                                    ->formatStateUsing(fn ($state) => $state ? 'Pastor Aktif' : 'Purna Tugas'),
                            ])->columnSpan(2),
                        ]),
                    ]),

                Section::make('Biografi & Riwayat Pelayanan')
                    ->schema([
                        TextEntry::make('biography')
                            ->label('')
                            ->hiddenLabel()
                            ->html()
                            ->placeholder('Belum ada biografi yang ditulis untuk pastor ini.'),
                    ]),

                Section::make('Susunan Anggota BPH (Dewan Pastoral Paroki)')
                    ->description('Daftar anggota BPH yang bertugas pada masa kepemimpinan pastor ini.')
                    ->schema([
                        RepeatableEntry::make('bphMembers')
                            ->label('')
                            ->hiddenLabel()
                            ->schema([
                                Grid::make(4)->schema([
                                    ImageEntry::make('photo')
                                        ->label('Foto')
                                        ->circular()
                                        ->size(40)
                                        ->defaultImageUrl(url('/images/default-avatar.png'))
                                        ->columnSpan(1),
                                    
                                    TextEntry::make('name')
                                        ->label('Nama Lengkap')
                                        ->weight('bold')
                                        ->columnSpan(2),
                                        
                                    TextEntry::make('position')
                                        ->label('Jabatan')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'Pastor Paroki' => 'primary',
                                            'Wakil Pastor' => 'info',
                                            'Bendahara I', 'Bendahara II' => 'warning',
                                            default => 'gray',
                                        })
                                        ->columnSpan(1),
                                ])->columnSpanFull(),
                            ])
                            ->columns(1),
                            
                        \Filament\Infolists\Components\Actions::make([
                            \Filament\Infolists\Components\Actions\Action::make('kelola_bph')
                                ->label('Kelola Anggota BPH')
                                ->icon('heroicon-m-pencil-square')
                                ->url(fn () => BphMemberResource::getUrl('index'))
                                ->color('primary'),
                        ]),
                    ]),
            ]);
    }
}
