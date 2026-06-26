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
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Kepala Keluarga')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('lingkungan_id')
                                ->label('Lingkungan')
                                ->relationship('lingkungan', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Pilih lingkungan tempat keluarga ini berdomisili'),

                            Forms\Components\TextInput::make('nama_kk')
                                ->label('Nama Kepala Keluarga')
                                ->required()
                                ->placeholder('Keluarga Marihot Siahaan')
                                ->helperText('Gunakan format: Keluarga [Nama Kepala Keluarga]'),

                            Forms\Components\TextInput::make('jumlah_anggota')
                                ->label('Jumlah Anggota Keluarga')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->helperText('Termasuk kepala keluarga'),

                            Forms\Components\TextInput::make('no_telp')
                                ->label('Nomor Telepon')
                                ->nullable()
                                ->placeholder('0812-3456-7890')
                                ->tel(),
                        ]),
                    ]),

                Section::make('Alamat')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->nullable()
                            ->rows(2)
                            ->placeholder('Jl. ..., RT/RW ..., Kel. ..., Parapat'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kk')
                    ->label('Nama Kepala Keluarga')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('lingkungan.name')
                    ->label('Lingkungan')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jumlah_anggota')
                    ->label('Anggota')
                    ->suffix(' jiwa')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->placeholder('-')
                    ->icon('heroicon-o-phone'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lingkungan_id')
                    ->relationship('lingkungan', 'name')
                    ->label('Filter Lingkungan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()->label('Hapus')->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('Belum Ada Data Kepala Keluarga')
            ->emptyStateDescription('Tambahkan data kepala keluarga per lingkungan')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah KK')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKepalaKeluargas::route('/'),
            'create' => Pages\CreateKepalaKeluarga::route('/create'),
            'edit' => Pages\EditKepalaKeluarga::route('/{record}/edit'),
        ];
    }
}
