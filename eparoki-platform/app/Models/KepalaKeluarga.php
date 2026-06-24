<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KepalaKeluarga extends Model {
    protected $table = 'kepala_keluarga';
    protected $fillable = [
        'nomor_kk',
        'lingkungan_id', 
        'nama_kk', 
        'nama_pasangan',
        'alamat', 
        'jumlah_anggota', 
        'no_telp',
        'is_active',
        'tanggal_bergabung',
        'status_umat',
        'keterangan'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_bergabung' => 'date',
    ];

    public function lingkungan() { return $this->belongsTo(Lingkungan::class); }
}