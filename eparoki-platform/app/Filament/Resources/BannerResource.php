<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Konten & Media';
    protected static ?string $navigationLabel = 'Banner Kegiatan';
    protected static ?string $modelLabel = 'Banner';
    protected static ?string $pluralModelLabel = 'Daftar Banner Kegiatan';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = Banner::active()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Banner')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Judul Banner')
                                ->required()
                                ->placeholder('Selamat Paskah 2025')
                                ->helperText('Judul singkat untuk identifikasi banner'),

                            Forms\Components\TextInput::make('order')
                                ->label('Urutan Tampil')
                                ->numeric()
                                ->default(0)
                                ->placeholder('0')
                                ->helperText('Angka lebih kecil = tampil lebih dulu di carousel aplikasi'),
                        ]),
                    ]),

                Section::make('Gambar Banner')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Gambar Banner')
                            ->required()
                            ->directory('banners')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Ukuran ideal: 1200x600 pixel. Format: JPG, PNG, atau WebP. Maks: 5MB')
                            ->imagePreviewHeight('200')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated'),
                    ]),

                Section::make('Pengaturan Tampil')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktifkan Banner')
                                ->default(true)
                                ->helperText('Banner hanya tampil di aplikasi jika diaktifkan')
                                ->onColor('success')
                                ->offColor('danger'),
                                
                            Forms\Components\Placeholder::make(''), // Kosong untuk layout grid 2 kolom
                        ]),

                        Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('start_date')
                                ->label('Mulai Tampil')
                                ->nullable()
                                ->displayFormat('d F Y')
                                ->placeholder('Pilih tanggal')
                                ->helperText('Kosongkan jika langsung tampil dari sekarang'),

                            Forms\Components\DatePicker::make('end_date')
                                ->label('Berhenti Tampil')
                                ->nullable()
                                ->displayFormat('d F Y')
                                ->placeholder('Pilih tanggal')
                                ->helperText('Kosongkan jika tidak ada batas waktu tampil')
                                ->afterOrEqual('start_date'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Preview')
                    ->width(120)
                    ->height(60)
                    ->extraImgAttributes(['style' => 'object-fit:cover; border-radius:6px;']),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Banner')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn (Banner $record): string => $record->status_label)
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Terjadwal' => 'warning',
                        'Nonaktif' => 'danger',
                        'Kedaluwarsa' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->placeholder('Sekarang')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->placeholder('Tidak terbatas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_filter')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif (Tampil Sekarang)',
                        'nonaktif' => 'Nonaktif / Terjadwal / Kedaluwarsa',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value'] ?? null) {
                        'aktif' => $query->active(),
                        'nonaktif' => $query->whereNotIn('id', Banner::active()->pluck('id')),
                        default => $query,
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
                Tables\Actions\EditAction::make()->label('Ubah'),
                
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (Banner $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn (Banner $record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (Banner $record) => $record->is_active ? 'danger' : 'success')
                    ->action(function (Banner $record) {
                        $record->update(['is_active' => !$record->is_active]);
                    }),
                    
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('aktifkan_semua')
                        ->label('Aktifkan yang Dipilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('nonaktifkan_semua')
                        ->label('Nonaktifkan yang Dipilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus yang Dipilih'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-photo')
            ->emptyStateHeading('Belum Ada Banner Kegiatan')
            ->emptyStateDescription('Tambahkan banner untuk menampilkan informasi kegiatan gereja di aplikasi mobile. Banner akan tampil sebagai carousel di halaman utama aplikasi.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Tambah Banner Pertama')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'view' => Pages\ViewBanner::route('/{record}'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
