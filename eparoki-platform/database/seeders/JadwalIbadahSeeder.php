<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\JadwalIbadah;
use App\Models\LiturgicalCalendar;
class JadwalIbadahSeeder extends Seeder {
    public function run() {
        $cal = LiturgicalCalendar::first();
        if($cal) {
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Senin", "time" => "06:00", "type" => "Misa Harian"]);
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Sabtu", "time" => "17:00", "type" => "Misa Vigili"]);
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Minggu", "time" => "07:00", "type" => "Misa I"]);
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Minggu", "time" => "09:30", "type" => "Misa II"]);
        }
    }
}