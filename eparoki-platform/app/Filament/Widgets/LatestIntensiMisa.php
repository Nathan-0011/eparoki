<?php
namespace App\Filament\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\IntensiMisa;

class LatestIntensiMisa extends BaseWidget
{
    protected int | string | array $columnSpan = "full";
    public function table(Table $table): Table
    {
        return $table
            ->query(IntensiMisa::currentWeek()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make("family_name")->label("Keluarga"),
                Tables\Columns\TextColumn::make("amount")->money("IDR")->label("Nominal"),
                Tables\Columns\TextColumn::make("description")->limit(50)->label("Keterangan"),
            ]);
    }
}
