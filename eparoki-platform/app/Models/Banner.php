<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Banner extends Model {
    protected $fillable = ["title", "image_path", "is_active", "start_date", "end_date", "order"];
    protected $casts = ["is_active" => "boolean", "start_date" => "date", "end_date" => "date"];
    public function scopeActive($query) {
        return $query->where("is_active", true)
            ->where(function($q) {
                $q->whereNull("start_date")->orWhere("start_date", "<=", now());
            })
            ->where(function($q) {
                $q->whereNull("end_date")->orWhere("end_date", ">=", now());
            });
    }
}