<?php
namespace App\Filament\Resources;
use App\Filament\Resources\LingkunganResource\Pages;
use App\Models\Lingkungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LingkunganResource extends Resource
{
    protected static ?string $model = Lingkungan::class;
    protected static ?string $navigationIcon = "heroicon-o-home";
    protected static ?string $navigationGroup = "Data Umat";
    protected static ?string $navigationLabel = "Lingkungan";
    protected static ?string $recordTitleAttribute = "name";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required()->placeholder("Lingkungan Santo Antonius"),
                Forms\Components\TextInput::make("patron_saint")->nullable(),
                Forms\Components\Textarea::make("description")->nullable()->rows(3),
                Forms\Components\FileUpload::make("photo")->directory("lingkungan")->image()->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("photo")->circular(),
                Tables\Columns\TextColumn::make("name")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("patron_saint"),
                Tables\Columns\TextColumn::make("kepala_keluarga_count")->counts("kepalaKeluarga")->label("Jumlah KK"),
                Tables\Columns\TextColumn::make("created_at")->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make("lihat_kk")->label("Lihat Daftar KK")->icon("heroicon-o-users")->url(fn (Lingkungan $record): string => KepalaKeluargaResource::getUrl("index", ["tableFilters" => ["lingkungan_id" => ["value" => $record->id]]])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListLingkungans::route("/"),
            "create" => Pages\CreateLingkungan::route("/create"),
            "edit" => Pages\EditLingkungan::route("/{record}/edit"),
        ];
    }
}
