<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiturgicalCalendarResource\Pages;
use App\Models\LiturgicalCalendar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LiturgicalCalendarResource extends Resource
{
    protected static ?string $model = LiturgicalCalendar::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Liturgi & Ibadah';
    protected static ?string $navigationLabel = 'Kalender Liturgi';
    protected static ?string $modelLabel = 'Kalender Liturgi';
    protected static ?string $pluralModelLabel = 'Kalender Liturgi';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 1;

    // Mapping bulan angka ke nama Indonesia
    public static function namaBulan(?int $bulan): string
    {
        $map = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $map[$bulan] ?? '-';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Periode Liturgi')
                    ->schema([
                        Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->required()
                            ->searchable()
                            ->default(now()->year)
                            ->options(array_combine(
                                range(2024, 2030),
                                range(2024, 2030)
                            )),

                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->required()
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ]),

                        Forms\Components\Select::make('week_number')
                            ->label('Minggu Ke')
                            ->required()
                            ->options([
                                1 => 'Minggu 1', 2 => 'Minggu 2',
                                3 => 'Minggu 3', 4 => 'Minggu 4', 5 => 'Minggu 5',
                            ]),
                    ])
                    ->columns(3),

                Section::make('Informasi Liturgi')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tema / Judul Minggu')
                            ->required()
                            ->placeholder('contoh: Minggu Paskah II, Minggu Biasa XIV')
                            ->helperText('Sesuaikan dengan kalender liturgi Katolik')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan Tambahan')
                            ->nullable()
                            ->rows(4)
                            ->placeholder('Warna liturgi, bacaan utama, catatan khusus, dll')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('month')
                    ->label('Bulan')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($state) => self::namaBulan($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('week_number')
                    ->label('Minggu')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => 'Minggu ' . $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tema Minggu')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('year', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(array_combine(range(2024, 2030), range(2024, 2030))),

                Tables\Filters\SelectFilter::make('month')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Kalender Liturgi?')
                    ->modalDescription('Data ini akan dihapus permanen dan tidak dapat dikembalikan.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus yang Dipilih'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-calendar')
            ->emptyStateHeading('Belum Ada Data Kalender')
            ->emptyStateDescription('Mulai tambahkan kalender liturgi untuk tahun ini.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Sekarang')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLiturgicalCalendars::route('/'),
            'create' => Pages\CreateLiturgicalCalendar::route('/create'),
            'view' => Pages\ViewLiturgicalCalendar::route('/{record}'),
            'edit' => Pages\EditLiturgicalCalendar::route('/{record}/edit'),
        ];
    }
}
