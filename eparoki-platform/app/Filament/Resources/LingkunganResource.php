<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LingkunganResource\Pages;
use App\Filament\Resources\LingkunganResource\RelationManagers\KepalaKeluargaRelationManager;
use App\Models\Lingkungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;

class LingkunganResource extends Resource
{
    protected static ?string $model = Lingkungan::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Data Umat';
    protected static ?string $navigationLabel = 'Lingkungan';
    protected static ?string $modelLabel = 'Lingkungan';
    protected static ?string $pluralModelLabel = 'Daftar Lingkungan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Lingkungan')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Lingkungan')
                                ->required()
                                ->placeholder('Lingkungan Santo Antonius')
                                ->helperText('Gunakan format: Lingkungan [Nama Santo Pelindung]'),
                                
                            Forms\Components\TextInput::make('patron_saint')
                                ->label('Santo Pelindung')
                                ->nullable()
                                ->placeholder('Santo Antonius dari Padua')
                                ->helperText('Nama lengkap santo pelindung lingkungan ini'),
                        ]),
                    ]),

                Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Lingkungan')
                            ->nullable()
                            ->rows(3)
                            ->placeholder('Wilayah yang dicakup, lokasi pertemuan rutin, dll'),
                            
                        Forms\Components\FileUpload::make('photo')
                            ->label('Foto Lingkungan')
                            ->nullable()
                            ->directory('lingkungan')
                            ->image()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Foto gereja lingkungan atau kegiatan lingkungan'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('')
                    ->circular(false)
                    ->width(60)
                    ->height(40)
                    ->extraImgAttributes(['style' => 'object-fit:cover; border-radius:6px;'])
                    ->defaultImageUrl(null),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lingkungan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('patron_saint')
                    ->label('Santo Pelindung')
                    ->placeholder('-')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('jumlah_kk')
                    ->label('Jumlah KK')
                    ->badge()
                    ->color('primary')
                    ->getStateUsing(fn ($record) => $record->kepalaKeluarga()->count() . ' KK'),

                Tables\Columns\TextColumn::make('jumlah_anggota')
                    ->label('Total Umat')
                    ->color('gray')
                    ->getStateUsing(fn ($record) => $record->kepalaKeluarga()->sum('jumlah_anggota') . ' jiwa'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()->label('Hapus')->requiresConfirmation(),
                Tables\Actions\Action::make('view_kk')
                    ->label('Daftar KK')
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->url(fn ($record) => LingkunganResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateIcon('heroicon-o-home')
            ->emptyStateHeading('Belum Ada Data Lingkungan')
            ->emptyStateDescription('Tambahkan lingkungan-lingkungan yang ada dalam Paroki Santo Fidelis Parapat')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah Lingkungan Pertama')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            KepalaKeluargaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLingkungans::route('/'),
            'create' => Pages\CreateLingkungan::route('/create'),
            'view' => Pages\ViewLingkungan::route('/{record}'),
            'edit' => Pages\EditLingkungan::route('/{record}/edit'),
        ];
    }
}
