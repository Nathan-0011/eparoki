<?php
namespace App\Filament\Resources;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $navigationIcon = "heroicon-o-photo";
    protected static ?string $navigationGroup = "Konten & Media";
    protected static ?string $navigationLabel = "Banner Kegiatan";
    protected static ?string $recordTitleAttribute = "title";
    protected static ?int $navigationSort = 1;

    // Badge info: jumlah banner aktif
    public static function getNavigationBadge(): ?string
    {
        $count = Banner::where('is_active', true)->count();
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
                Forms\Components\TextInput::make("title")->required()->placeholder("Selamat Paskah 2025"),
                Forms\Components\FileUpload::make("image_path")->directory("banners")->image()->maxSize(5120)->imageEditor()->required(),
                Forms\Components\Toggle::make("is_active")->default(true)->label("Tampilkan di Aplikasi"),
                Forms\Components\DatePicker::make("start_date")->nullable()->label("Mulai Tampil"),
                Forms\Components\DatePicker::make("end_date")->nullable()->label("Berhenti Tampil"),
                Forms\Components\TextInput::make("order")->numeric()->default(0)->hint("Urutan tampil di carousel, angka kecil = tampil lebih dulu"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("image_path")->height(50)->width(80),
                Tables\Columns\TextColumn::make("title")->searchable(),
                Tables\Columns\ToggleColumn::make("is_active"),
                Tables\Columns\TextColumn::make("start_date")->date(),
                Tables\Columns\TextColumn::make("end_date")->date(),
                Tables\Columns\TextColumn::make("order")->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListBanners::route("/"),
            "create" => Pages\CreateBanner::route("/create"),
            "edit" => Pages\EditBanner::route("/{record}/edit"),
        ];
    }
}
