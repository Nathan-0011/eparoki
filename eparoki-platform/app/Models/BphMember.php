<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BphMember extends Model {
    protected $fillable = ["pastor_id", "name", "position", "photo", "period_start", "period_end"];
    public function pastor() { return $this->belongsTo(Pastor::class); }
}