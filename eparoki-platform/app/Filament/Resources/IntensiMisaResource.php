<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IntensiMisaResource\Pages;
use App\Models\IntensiMisa;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IntensiMisaResource extends Resource
{
    protected static ?string $model = IntensiMisa::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Keuangan & Intensi';
    protected static ?string $navigationLabel = 'Intensi Misa';
    protected static ?string $modelLabel = 'Intensi Misa';
    protected static ?string $pluralModelLabel = 'Daftar Intensi Misa';
    protected static ?string $recordTitleAttribute = 'family_name';
    protected static ?int $navigationSort = 1;

    // Badge merah: jumlah intensi aktif minggu ini
    public static function getNavigationBadge(): ?string
    {
        $count = IntensiMisa::where('is_archived', false)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    // Helper: format rupiah
    public static function formatRupiah(?int $amount): string
    {
        if ($amount === null) return '-';
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pemberi Intensi')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('family_name')
                                ->label('Nama Keluarga / Pemberi Intensi')
                                ->required()
                                ->placeholder('Keluarga Jonathan Simamora')
                                ->helperText('Tulis nama lengkap keluarga atau perorangan yang memberikan intensi'),

                            Forms\Components\TextInput::make('amount')
                                ->label('Nominal Persembahan (Rp)')
                                ->numeric()
                                ->nullable()
                                ->prefix('Rp')
                                ->placeholder('500000')
                                ->helperText('Kosongkan jika tidak ada nominal'),
                        ]),
                    ]),

                Section::make('Keterangan Intensi')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan Intensi')
                            ->required()
                            ->rows(4)
                            ->placeholder('Contoh: Intensi syukur atas penerimaan putranya di Polri. Semoga diberkati dalam pengabdian kepada negara.')
                            ->helperText('Tuliskan tujuan/maksud intensi misa secara lengkap'),
                    ]),

                Section::make('Periode')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('week_date')
                                ->label('Tanggal Minggu Intensi')
                                ->required()
                                ->default(Carbon::now()->startOfWeek(Carbon::MONDAY))
                                ->displayFormat('d F Y')
                                ->helperText('Otomatis terisi tanggal Senin minggu ini. Ubah jika perlu.'),

                            Forms\Components\Toggle::make('is_archived')
                                ->label('Status Arsip')
                                ->default(false)
                                ->onColor('warning')
                                ->offColor('success')
                                ->helperText('Centang jika intensi ini sudah diarsipkan (minggu lalu)'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('week_date')
                    ->label('Minggu')
                    ->badge()
                    ->formatStateUsing(fn ($state) => 'Minggu ' . Carbon::parse($state)->translatedFormat('d M Y'))
                    ->color(fn ($state) => Carbon::parse($state)->toDateString() === $startOfWeek ? 'success' : 'gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('family_name')
                    ->label('Nama Keluarga')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => self::formatRupiah($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan Intensi')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->description),

                Tables\Columns\TextColumn::make('is_archived')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Diarsipkan' : 'Minggu Ini')
                    ->color(fn ($state) => $state ? 'gray' : 'success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dicatat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('week_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_intensi')
                    ->label('Status Intensi')
                    ->options([
                        'aktif' => 'Minggu Ini (Aktif)',
                        'arsip' => 'Sudah Diarsipkan',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value'] ?? null) {
                        'aktif' => $query->where('is_archived', false),
                        'arsip' => $query->where('is_archived', true),
                        default => $query,
                    }),

                Tables\Filters\Filter::make('periode_minggu')
                    ->label('Periode Minggu')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal')->displayFormat('d M Y'),
                        Forms\Components\DatePicker::make('until')->label('Sampai Tanggal')->displayFormat('d M Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('week_date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('week_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Intensi Misa?')
                    ->modalDescription('Data intensi ini akan dihapus permanen.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus yang Dipilih'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-heart')
            ->emptyStateHeading('Belum Ada Intensi Misa Minggu Ini')
            ->emptyStateDescription('Tambahkan intensi misa yang diterima untuk minggu berjalan ini.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah Intensi Sekarang')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIntensiMisas::route('/'),
            'create' => Pages\CreateIntensiMisa::route('/create'),
            'edit' => Pages\EditIntensiMisa::route('/{record}/edit'),
        ];
    }
}
