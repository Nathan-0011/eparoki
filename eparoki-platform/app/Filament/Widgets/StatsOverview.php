<?php
namespace App\Filament\Widgets;
use App\Models\Lingkungan;
use App\Models\KepalaKeluarga;
use App\Models\IntensiMisa;
use App\Models\JadwalIbadah;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Total Lingkungan", Lingkungan::count())
                ->icon("heroicon-o-home"),
            Stat::make("Total Kepala Keluarga", KepalaKeluarga::count())
                ->icon("heroicon-o-users"),
            Stat::make("Intensi Misa Minggu Ini", IntensiMisa::currentWeek()->count())
                ->icon("heroicon-o-heart"),
            Stat::make("Jadwal Ibadah Hari Ini", JadwalIbadah::where("day_of_week", array_values(["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"])[date("w")])->count())
                ->icon("heroicon-o-clock"),
        ];
    }
}
