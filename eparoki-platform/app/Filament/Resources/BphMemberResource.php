<?php
namespace App\Filament\Resources;
use App\Filament\Resources\BphMemberResource\Pages;
use App\Models\BphMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BphMemberResource extends Resource
{
    protected static ?string $model = BphMember::class;
    protected static ?string $navigationIcon = "heroicon-o-identification";
    protected static ?string $navigationGroup = "Kepemimpinan";
    protected static ?string $navigationLabel = "Anggota BPH";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make("pastor_id")->relationship("pastor", "name")->required(),
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\Select::make("position")->options(["Pastor Paroki"=>"Pastor Paroki","Wakil Pastor"=>"Wakil Pastor","Ketua Dewan Pastoral"=>"Ketua Dewan Pastoral","Sekretaris"=>"Sekretaris","Bendahara I"=>"Bendahara I","Bendahara II"=>"Bendahara II","Koordinator Liturgi"=>"Koordinator Liturgi","Koordinator Sosial"=>"Koordinator Sosial"])->required(),
                Forms\Components\FileUpload::make("photo")->directory("bph")->image()->maxSize(2048),
                Forms\Components\TextInput::make("period_start")->numeric()->required(),
                Forms\Components\TextInput::make("period_end")->numeric()->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("photo")->circular(),
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("position")->badge(),
                Tables\Columns\TextColumn::make("pastor.name"),
                Tables\Columns\TextColumn::make("period_start"),
                Tables\Columns\TextColumn::make("period_end"),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListBphMembers::route("/"),
            "create" => Pages\CreateBphMember::route("/create"),
            "edit" => Pages\EditBphMember::route("/{record}/edit"),
        ];
    }
}
