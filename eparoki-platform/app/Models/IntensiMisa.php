<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class IntensiMisa extends Model {
    protected $table = "intensi_misa";
    protected $fillable = ["family_name", "amount", "description", "week_date", "is_archived"];
    protected $casts = ["is_archived" => "boolean", "week_date" => "date"];
    public function scopeCurrentWeek($query) { return $query->where("is_archived", false); }
}