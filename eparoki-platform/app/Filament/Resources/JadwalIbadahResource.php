<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalIbadahResource\Pages;
use App\Models\JadwalIbadah;
use App\Models\LiturgicalCalendar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JadwalIbadahResource extends Resource
{
    protected static ?string $model = JadwalIbadah::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Liturgi & Ibadah';
    protected static ?string $navigationLabel = 'Jadwal Ibadah';
    protected static ?string $modelLabel = 'Jadwal Ibadah';
    protected static ?string $pluralModelLabel = 'Jadwal Ibadah';
    protected static ?int $navigationSort = 2;

    // Helper: opsi nama bulan Indonesia
    private static function opsBulan(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ─── SECTION 1: Keterkaitan Kalender ───
                Section::make('Periode Kalender Liturgi')
                    ->description('Pilih periode dari kalender liturgi yang sudah ada, atau kosongkan untuk jadwal mandiri.')
                    ->schema([
                        Forms\Components\Select::make('calendar_id')
                            ->label('Kalender Liturgi')
                            ->nullable()
                            ->relationship(
                                name: 'calendar',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query) => $query->orderBy('year', 'desc')->orderBy('month')->orderBy('week_number'),
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) =>
                                "{$record->year} — " . self::opsBulan()[$record->month] . " — Minggu {$record->week_number}: {$record->title}"
                            )
                            ->searchable(['title'])
                            ->preload()
                            ->placeholder('Pilih kalender (opsional)'),
                    ])
                    ->collapsible(),

                // ─── SECTION 2: Identitas Ibadah ───
                Section::make('Identitas Ibadah')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('nama_ibadah')
                                ->label('Nama Ibadah')
                                ->required()
                                ->placeholder('Contoh: Misa Natal, Misa Harian')
                                ->datalist([
                                    'Misa Harian',
                                    'Misa Mingguan',
                                    'Misa Paskah',
                                    'Misa Natal',
                                    'Jalan Salib',
                                    'Doa Rosario',
                                    'Adorasi Ekaristi',
                                    'Novena',
                                ]),

                            Forms\Components\Select::make('jenis_ibadah')
                                ->label('Jenis Ibadah')
                                ->required()
                                ->options([
                                    'Harian' => 'Harian',
                                    'Mingguan' => 'Mingguan',
                                    'Bulanan' => 'Bulanan',
                                    'Perayaan Khusus' => 'Perayaan Khusus',
                                ])
                                ->default('Mingguan'),
                        ]),
                    ]),

                // ─── SECTION 3: Waktu Pelaksanaan ───
                Section::make('Waktu Pelaksanaan')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('day_of_week')
                                ->label('Hari')
                                ->required()
                                ->options([
                                    'Senin' => 'Senin',
                                    'Selasa' => 'Selasa',
                                    'Rabu' => 'Rabu',
                                    'Kamis' => 'Kamis',
                                    'Jumat' => 'Jumat',
                                    'Sabtu' => 'Sabtu',
                                    'Minggu' => 'Minggu',
                                ]),

                            Forms\Components\TimePicker::make('time')
                                ->label('Jam Mulai')
                                ->required()
                                ->seconds(false),

                            Forms\Components\TimePicker::make('jam_selesai')
                                ->label('Jam Selesai')
                                ->nullable()
                                ->seconds(false),
                        ]),

                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Spesifik')
                            ->nullable()
                            ->hint('Isi hanya untuk ibadah pada tanggal tertentu (misal: Misa Natal 25 Des)')
                            ->displayFormat('d M Y'),
                    ]),

                // ─── SECTION 4: Lokasi & Pemimpin ───
                Section::make('Lokasi & Pemimpin')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('location')
                                ->label('Lokasi')
                                ->default('Gereja Santo Fidelis Parapat')
                                ->required(),

                            Forms\Components\TextInput::make('celebrant')
                                ->label('Pemimpin Ibadah')
                                ->nullable()
                                ->placeholder('Contoh: Rm. Fidelis Tambunan'),
                        ]),
                    ]),

                // ─── SECTION 5: Keterangan & Status ───
                Section::make('Keterangan & Status')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->nullable()
                            ->rows(3)
                            ->placeholder('Catatan khusus, persiapan yang diperlukan, dll.'),

                        Forms\Components\Select::make('status')
                            ->label('Status Ibadah')
                            ->required()
                            ->options([
                                'Aktif' => 'Aktif',
                                'Selesai' => 'Selesai',
                                'Dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('Aktif'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_ibadah')
                    ->label('Nama Ibadah')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->jenis_ibadah),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->placeholder('Rutin')
                    ->sortable(),

                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Minggu' => 'danger',
                        'Sabtu' => 'warning',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('time')
                    ->label('Jam Mulai')
                    ->formatStateUsing(fn ($state) => $state
                        ? \Carbon\Carbon::parse($state)->format('H:i')
                        : '–'),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(30),

                Tables\Columns\TextColumn::make('celebrant')
                    ->label('Pemimpin')
                    ->placeholder('–')
                    ->limit(25),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Aktif' => 'success',
                        'Selesai' => 'gray',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('day_of_week')
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_ibadah')
                    ->label('Jenis Ibadah')
                    ->options([
                        'Harian' => 'Harian',
                        'Mingguan' => 'Mingguan',
                        'Bulanan' => 'Bulanan',
                        'Perayaan Khusus' => 'Perayaan Khusus',
                    ]),

                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Jadwal Ibadah?')
                    ->modalDescription('Data ini akan dihapus permanen.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus yang Dipilih'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-clock')
            ->emptyStateHeading('Belum Ada Jadwal Ibadah')
            ->emptyStateDescription('Mulai tambahkan jadwal ibadah rutin atau perayaan khusus.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah Jadwal')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalIbadahs::route('/'),
            'create' => Pages\CreateJadwalIbadah::route('/create'),
            'view' => Pages\ViewJadwalIbadah::route('/{record}'),
            'edit' => Pages\EditJadwalIbadah::route('/{record}/edit'),
        ];
    }
}
