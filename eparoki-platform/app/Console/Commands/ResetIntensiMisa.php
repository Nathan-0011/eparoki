<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\IntensiMisa;

class ResetIntensiMisa extends Command {
    protected $signature = "intensi:reset";
    protected $description = "Reset intensi misa minggu lalu (set is_archived=true)";
    public function handle() {
        $lastWeekMonday = now()->subWeek()->startOfWeek()->toDateString();
        $count = IntensiMisa::where("week_date", "<=", $lastWeekMonday)
            ->where("is_archived", false)
            ->update(["is_archived" => true]);
        $this->info("Berhasil mengarsipkan {$count} intensi misa.");
    }
}