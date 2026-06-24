<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalIbadah extends Model {
    protected $table = 'jadwal_ibadah';
    protected $fillable = [
        'calendar_id',   // nullable — opsional
        'nama_ibadah',
        'jenis_ibadah',
        'day_of_week',
        'tanggal',
        'time',          // jam mulai
        'jam_selesai',
        'type',          // legacy field
        'celebrant',
        'location',
        'keterangan',
        'status',
    ];
    protected $casts = [
        'tanggal' => 'date',
        'calendar_id' => 'integer',
    ];
    public function calendar() { return $this->belongsTo(LiturgicalCalendar::class, 'calendar_id'); }
}