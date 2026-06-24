<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pastor extends Model {
    protected $fillable = ["name", "photo", "period_start", "period_end", "biography", "is_active"];
    protected $casts = ["is_active" => "boolean"];
    public function bphMembers() { return $this->hasMany(BphMember::class); }
    public function scopeActive($query) { return $query->where("is_active", true); }
}