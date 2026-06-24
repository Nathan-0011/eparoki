<?php
namespace App\Filament\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\JadwalIbadah;

class JadwalHariIni extends BaseWidget
{
    protected int | string | array $columnSpan = "full";
    public function table(Table $table): Table
    {
        return $table
            ->query(JadwalIbadah::where("day_of_week", array_values(["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"])[date("w")]))
            ->columns([
                Tables\Columns\TextColumn::make("time")->time("H:i")->label("Waktu"),
                Tables\Columns\TextColumn::make("type")->badge()->label("Jenis Ibadah"),
                Tables\Columns\TextColumn::make("celebrant")->label("Selebran"),
            ]);
    }
}
