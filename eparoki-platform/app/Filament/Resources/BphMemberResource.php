<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BphMemberResource\Pages;
use App\Models\BphMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BphMemberResource extends Resource
{
    protected static ?string $model = BphMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Kepemimpinan';
    protected static ?string $navigationLabel = 'Anggota BPH';
    protected static ?string $modelLabel = 'Anggota BPH';
    protected static ?string $pluralModelLabel = 'Daftar Anggota BPH';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Anggota')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('pastor_id')
                                ->label('Pastor / Periode BPH')
                                ->required()
                                ->relationship('pastor', 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('Pilih pastor yang menjadi pastor paroki pada periode ini'),

                            Forms\Components\Select::make('position')
                                ->label('Jabatan dalam BPH')
                                ->required()
                                ->options([
                                    'Pastor Paroki' => 'Pastor Paroki',
                                    'Wakil Pastor' => 'Wakil Pastor',
                                    'Ketua Dewan Pastoral' => 'Ketua Dewan Pastoral',
                                    'Sekretaris' => 'Sekretaris',
                                    'Bendahara I' => 'Bendahara I',
                                    'Bendahara II' => 'Bendahara II',
                                    'Koordinator Liturgi' => 'Koordinator Liturgi',
                                    'Koordinator Sosial' => 'Koordinator Sosial',
                                    'Koordinator Pendidikan' => 'Koordinator Pendidikan',
                                    'Koordinator Kepemudaan' => 'Koordinator Kepemudaan',
                                ]),

                            Forms\Components\TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->placeholder('Nama anggota BPH'),

                            Forms\Components\FileUpload::make('photo')
                                ->label('Foto')
                                ->nullable()
                                ->directory('bph')
                                ->image()
                                ->imageEditor()
                                ->circleCropper()
                                ->maxSize(1024)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

                            Forms\Components\TextInput::make('period_start')
                                ->label('Tahun Mulai')
                                ->numeric()
                                ->required()
                                ->placeholder('2021')
                                ->minValue(1900)
                                ->maxValue(2099),

                            Forms\Components\TextInput::make('period_end')
                                ->label('Tahun Selesai')
                                ->numeric()
                                ->nullable()
                                ->placeholder('Kosongkan jika masih menjabat')
                                ->minValue(1900)
                                ->maxValue(2099)
                                ->gt('period_start'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pastor Paroki' => 'primary',
                        'Wakil Pastor' => 'info',
                        'Bendahara I', 'Bendahara II' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('pastor.name')
                    ->label('Periode Pastor')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('periode')
                    ->label('Masa Jabatan')
                    ->formatStateUsing(function ($record) {
                        return "{$record->period_start} — " . ($record->period_end ?? 'Sekarang');
                    })
                    ->sortable(['period_start']),
            ])
            ->defaultSort('period_start', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('pastor_id')
                    ->label('Periode Pastor')
                    ->relationship('pastor', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus yang Dipilih'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('Belum Ada Anggota BPH')
            ->emptyStateDescription('Tambahkan struktur kepengurusan dewan pastoral.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah Anggota')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBphMembers::route('/'),
            'create' => Pages\CreateBphMember::route('/create'),
            'edit' => Pages\EditBphMember::route('/{record}/edit'),
        ];
    }
}
