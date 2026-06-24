<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LiturgicalCalendar extends Model {
    protected $fillable = ["year", "month", "week_number", "title", "description"];
    public function jadwalIbadah() { return $this->hasMany(JadwalIbadah::class, "calendar_id"); }
    public function scopeByYearMonth($query, $year, $month) {
        return $query->where("year", $year)->where("month", $month);
    }
}