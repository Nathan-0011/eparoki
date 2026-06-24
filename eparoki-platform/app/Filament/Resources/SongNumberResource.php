<?php
namespace App\Filament\Resources;
use App\Filament\Resources\SongNumberResource\Pages;
use App\Models\SongNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SongNumberResource extends Resource
{
    protected static ?string $model = SongNumber::class;
    protected static ?string $navigationIcon = "heroicon-o-musical-note";
    protected static ?string $navigationGroup = "Perangkat IoT";
    protected static ?string $navigationLabel = "Nomor Lagu";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("song_number")->required()->maxLength(4)->numeric()->placeholder("2100"),
                Forms\Components\TextInput::make("device_id")->nullable()->placeholder("display-01"),
                Forms\Components\Select::make("status")->options(["pending"=>"pending", "sent"=>"sent", "failed"=>"failed"])->default("pending"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("song_number")->badge()->size(Tables\Columns\TextColumn\TextColumnSize::Large),
                Tables\Columns\TextColumn::make("status")->badge()->color(fn ($state) => match($state){"pending"=>"warning","sent"=>"success","failed"=>"danger",default=>"primary"}),
                Tables\Columns\TextColumn::make("device_id"),
                Tables\Columns\TextColumn::make("sent_at")->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListSongNumbers::route("/"),
            "create" => Pages\CreateSongNumber::route("/create"),
            "edit" => Pages\EditSongNumber::route("/{record}/edit"),
        ];
    }
}
