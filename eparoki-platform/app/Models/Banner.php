<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Banner extends Model
{
    protected $fillable = ['title', 'image_path', 'is_active', 'start_date', 'end_date', 'order'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('start_date')->orWhere('start_date', '<=', today()))
            ->where(fn (Builder $q) => $q->whereNull('end_date')->orWhere('end_date', '>=', today()))
            ->orderBy('order', 'asc');
    }

    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) {
            return 'Nonaktif';
        }
        
        $today = Carbon::today();
        
        if ($this->start_date && $this->start_date > $today) {
            return 'Terjadwal';
        }
        
        if ($this->end_date && $this->end_date < $today) {
            return 'Kedaluwarsa';
        }
        
        return 'Aktif';
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) return '';
        
        // Cek jika image_path sudah berupa URL lengkap
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        
        return url(Storage::url($this->image_path));
    }
}