<?php

namespace App\Filament\Resources\LiturgicalCalendarResource\Pages;

use App\Filament\Resources\LiturgicalCalendarResource;
use App\Filament\Resources\JadwalIbadahResource;
use App\Models\JadwalIbadah;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewLiturgicalCalendar extends ViewRecord
{
    protected static string $resource = LiturgicalCalendarResource::class;

    // Override judul halaman
    public function getHeading(): string
    {
        $record = $this->getRecord();
        $namaBulan = LiturgicalCalendarResource::namaBulan($record->month);
        return "{$record->year} • {$namaBulan} • Minggu {$record->week_number}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Ubah Data'),
            // Tombol tambah jadwal ibadah untuk kalender ini
            Actions\Action::make('tambah_jadwal')
                ->label('Tambah Jadwal Ibadah')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(fn () => JadwalIbadahResource::getUrl('create') . '?calendar_id=' . $this->getRecord()->id),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail Minggu Liturgi')
                    ->schema([
                        TextEntry::make('year')
                            ->label('Tahun'),

                        TextEntry::make('month')
                            ->label('Bulan')
                            ->badge()
                            ->color('primary')
                            ->formatStateUsing(fn ($state) => LiturgicalCalendarResource::namaBulan($state)),

                        TextEntry::make('week_number')
                            ->label('Minggu Ke')
                            ->badge()
                            ->color('gray')
                            ->formatStateUsing(fn ($state) => 'Minggu ' . $state),

                        TextEntry::make('title')
                            ->label('Tema / Judul Minggu')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->columnSpanFull(),

                        TextEntry::make('description')
                            ->label('Keterangan Tambahan')
                            ->placeholder('Tidak ada keterangan tambahan.')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Jadwal Ibadah Minggu Ini')
                    ->schema([
                        \Filament\Infolists\Components\ViewEntry::make('jadwal')
                            ->label('')
                            ->view('filament.infolists.components.jadwal-list')
                            ->viewData(fn ($record) => [
                                'jadwals' => JadwalIbadah::where('calendar_id', $record->id)
                                    ->orderBy('day_of_week')
                                    ->orderBy('time')
                                    ->get(),
                                'calendarId' => $record->id,
                            ]),
                    ]),
            ]);
    }
}
