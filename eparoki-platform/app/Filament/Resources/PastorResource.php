<?php
namespace App\Filament\Resources;
use App\Filament\Resources\PastorResource\Pages;
use App\Models\Pastor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PastorResource extends Resource
{
    protected static ?string $model = Pastor::class;
    protected static ?string $navigationIcon = "heroicon-o-user-circle";
    protected static ?string $navigationGroup = "Kepemimpinan";
    protected static ?string $navigationLabel = "Pastor Paroki";
    protected static ?string $recordTitleAttribute = "name";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required()->placeholder("Rm. Fidelis Tambunan, OFMCap"),
                Forms\Components\FileUpload::make("photo")->directory("pastors")->image()->maxSize(2048)->avatar(),
                Forms\Components\TextInput::make("period_start")->numeric()->required()->placeholder("2021"),
                Forms\Components\TextInput::make("period_end")->numeric()->nullable()->placeholder("Kosongkan jika masih aktif"),
                Forms\Components\Toggle::make("is_active")->label("Pastor Aktif Saat Ini")->hint("Hanya 1 pastor yang boleh aktif"),
                Forms\Components\RichEditor::make("biography")->nullable()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("photo")->circular()->size(40),
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("period_start"),
                Tables\Columns\TextColumn::make("period_end")->formatStateUsing(fn ($state) => $state ?? "Sekarang"),
                Tables\Columns\IconColumn::make("is_active")->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListPastors::route("/"),
            "create" => Pages\CreatePastor::route("/create"),
            "edit" => Pages\EditPastor::route("/{record}/edit"),
        ];
    }
}
