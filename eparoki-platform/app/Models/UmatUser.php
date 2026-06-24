<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UmatUser extends Authenticatable {
    use HasApiTokens;
    protected $fillable = ["name", "email", "google_id", "avatar", "lingkungan_id"];
    public function lingkungan() { return $this->belongsTo(Lingkungan::class); }
}