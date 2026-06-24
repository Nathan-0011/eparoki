<?php
namespace App\Filament\Resources;
use App\Filament\Resources\IntensiMisaResource\Pages;
use App\Models\IntensiMisa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;

class IntensiMisaResource extends Resource
{
    protected static ?string $model = IntensiMisa::class;
    protected static ?string $navigationIcon = "heroicon-o-heart";
    protected static ?string $navigationGroup = "Keuangan & Intensi";
    protected static ?string $navigationLabel = "Intensi Misa";
    protected static ?string $recordTitleAttribute = "family_name";
    protected static ?int $navigationSort = 1;

    // Badge merah: jumlah intensi aktif minggu ini
    public static function getNavigationBadge(): ?string
    {
        $count = IntensiMisa::currentWeek()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("family_name")->required()->placeholder("Keluarga Jonathan Simamora"),
                Forms\Components\TextInput::make("amount")->numeric()->nullable()->prefix("Rp")->placeholder("500000"),
                Forms\Components\Textarea::make("description")->required()->rows(3)->placeholder("Intensi syukur atas..."),
                Forms\Components\DatePicker::make("week_date")->required()->default(now()->startOfWeek())->hint("Tanggal Senin dari minggu intensi ini"),
                Forms\Components\Toggle::make("is_archived")->default(false)->label("Sudah Diarsipkan"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("family_name")->searchable(),
                Tables\Columns\TextColumn::make("amount")->money("IDR"),
                Tables\Columns\TextColumn::make("description")->limit(60),
                Tables\Columns\TextColumn::make("week_date")->badge()->date("d M Y")->label("Minggu"),
                Tables\Columns\IconColumn::make("is_archived")->boolean(),
            ])
            ->filters([
                Tables\Filters\Filter::make("is_archived")->query(fn ($query) => $query->where("is_archived", true)),
                Tables\Filters\Filter::make("week_date")->form([Forms\Components\DatePicker::make("week_date")])->query(function ($query, array $data) { return $data["week_date"] ? $query->whereDate("week_date", $data["week_date"]) : $query; }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListIntensiMisas::route("/"),
            "create" => Pages\CreateIntensiMisa::route("/create"),
            "edit" => Pages\EditIntensiMisa::route("/{record}/edit"),
        ];
    }
}
