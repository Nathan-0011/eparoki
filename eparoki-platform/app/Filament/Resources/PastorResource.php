<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PastorResource\Pages;
use App\Models\Pastor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PastorResource extends Resource
{
    protected static ?string $model = Pastor::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Kepemimpinan';
    protected static ?string $navigationLabel = 'Pastor Paroki';
    protected static ?string $modelLabel = 'Pastor';
    protected static ?string $pluralModelLabel = 'Daftar Pastor';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Pastor')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Lengkap Pastor')
                                ->required()
                                ->placeholder('Rm. Fidelis Tambunan, OFMCap'),

                            Forms\Components\FileUpload::make('photo')
                                ->label('Foto Pastor')
                                ->nullable()
                                ->directory('pastors')
                                ->image()
                                ->imageEditor()
                                ->circleCropper()
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                        ]),
                    ]),

                Section::make('Periode Pelayanan')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('period_start')
                                ->label('Tahun Mulai Bertugas')
                                ->numeric()
                                ->required()
                                ->placeholder('2021')
                                ->minValue(1900)
                                ->maxValue(2099),

                            Forms\Components\TextInput::make('period_end')
                                ->label('Tahun Selesai Bertugas')
                                ->numeric()
                                ->nullable()
                                ->placeholder('Kosongkan jika masih aktif')
                                ->helperText('Biarkan kosong jika pastor masih bertugas saat ini')
                                ->minValue(1900)
                                ->maxValue(2099)
                                ->gt('period_start'),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Pastor Aktif Saat Ini')
                                ->helperText('Aktifkan hanya untuk 1 pastor yang sedang bertugas. Pastor lain akan otomatis dinonaktifkan.')
                                ->onColor('success')
                                ->offColor('gray')
                                ->columnSpanFull()
                                ->live()
                                ->afterStateUpdated(function ($state, $record) {
                                    // Jika di set aktif, nonaktifkan pastor lain
                                    if ($state) {
                                        if ($record) {
                                            Pastor::where('id', '!=', $record->id)->update(['is_active' => false]);
                                        } else {
                                            Pastor::query()->update(['is_active' => false]);
                                        }
                                    }
                                }),
                        ]),
                    ]),

                Section::make('Biografi')
                    ->schema([
                        Forms\Components\RichEditor::make('biography')
                            ->label('Biografi & Riwayat Pelayanan')
                            ->nullable()
                            ->toolbarButtons([
                                'bold', 'italic', 'bulletList', 'orderedList', 'h3', 'blockquote',
                            ])
                            ->placeholder('Tuliskan latar belakang, riwayat pendidikan, dan pelayanan pastor...'),
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
                    ->size(45)
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pastor')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->formatStateUsing(function ($record) {
                        return "{$record->period_start} — " . ($record->period_end ?? 'Sekarang');
                    })
                    ->sortable(['period_start']),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('bphMembers_count')
                    ->label('Anggota BPH')
                    ->counts('bphMembers')
                    ->formatStateUsing(fn ($state) => "{$state} orang")
                    ->alignCenter(),
            ])
            ->defaultSort('period_start', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Purna Tugas'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus yang Dipilih'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-user-circle')
            ->emptyStateHeading('Belum Ada Data Pastor')
            ->emptyStateDescription('Tambahkan riwayat pastor paroki dari masa ke masa.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah Pastor Pertama')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPastors::route('/'),
            'create' => Pages\CreatePastor::route('/create'),
            'view' => Pages\ViewPastor::route('/{record}'),
            'edit' => Pages\EditPastor::route('/{record}/edit'),
        ];
    }
}
