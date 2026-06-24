<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KepalaKeluargaResource\Pages;
use App\Models\KepalaKeluarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;

class KepalaKeluargaResource extends Resource
{
    protected static ?string $model = KepalaKeluarga::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Data Umat';
    protected static ?string $navigationLabel = 'Kepala Keluarga';
    protected static ?string $modelLabel = 'Kepala Keluarga';
    protected static ?string $pluralModelLabel = 'Daftar Kepala Keluarga';
    protected static ?string $recordTitleAttribute = 'nama_kk';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Keluarga')
                    ->description('Informasi utama terkait keluarga dan wilayahnya.')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('nomor_kk')
                                ->label('Nomor Kartu Keluarga (Gereja)')
                                ->placeholder('Contoh: KK-2025-001')
                                ->unique(ignoreRecord: true)
                                ->nullable(),

                            Forms\Components\Select::make('lingkungan_id')
                                ->label('Lingkungan')
                                ->relationship('lingkungan', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),

                            Forms\Components\TextInput::make('nama_kk')
                                ->label('Nama Kepala Keluarga')
                                ->required()
                                ->placeholder('Contoh: Marihot Siahaan'),

                            Forms\Components\TextInput::make('nama_pasangan')
                                ->label('Nama Pasangan')
                                ->nullable()
                                ->placeholder('Suami / Istri (jika ada)'),

                            Forms\Components\TextInput::make('no_telp')
                                ->label('Nomor Telepon')
                                ->tel()
                                ->nullable()
                                ->placeholder('08xx-xxxx-xxxx'),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->inline(false),
                        ]),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->nullable()
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Gerejawi')
                    ->description('Informasi keanggotaan umat.')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('tanggal_bergabung')
                                ->label('Tanggal Bergabung')
                                ->nullable()
                                ->displayFormat('d M Y'),

                            Forms\Components\Select::make('status_umat')
                                ->label('Status Umat')
                                ->options([
                                    'Aktif' => 'Aktif',
                                    'Pindah Paroki' => 'Pindah Paroki',
                                    'Meninggal Dunia' => 'Meninggal Dunia',
                                    'Lainnya' => 'Lainnya',
                                ])
                                ->default('Aktif')
                                ->required(),
                        ]),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan / Catatan')
                            ->nullable()
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_kk')
                    ->label('No. KK')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nama_kk')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('lingkungan.name')
                    ->label('Lingkungan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_anggota')
                    ->label('Jml Anggota')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('nama_kk')
            ->filters([
                Tables\Filters\SelectFilter::make('lingkungan_id')
                    ->label('Lingkungan')
                    ->relationship('lingkungan', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('Belum Ada Kepala Keluarga')
            ->emptyStateDescription('Mulai daftarkan kepala keluarga ke lingkungan paroki.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah Keluarga')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKepalaKeluargas::route('/'),
            'create' => Pages\CreateKepalaKeluarga::route('/create'),
            'view' => Pages\ViewKepalaKeluarga::route('/{record}'),
            'edit' => Pages\EditKepalaKeluarga::route('/{record}/edit'),
        ];
    }
}
