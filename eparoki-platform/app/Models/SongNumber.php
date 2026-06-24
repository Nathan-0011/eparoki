<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SongNumber extends Model {
    protected $fillable = ["song_number", "sent_at", "device_id", "status"];
    protected $casts = ["sent_at" => "datetime"];
    public function scopeLatest($query) { return $query->orderBy("sent_at", "desc"); }
}