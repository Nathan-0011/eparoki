<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lingkungan extends Model {
    protected $table = "lingkungan";
    protected $fillable = ["name", "patron_saint", "description", "photo"];
    public function kepalaKeluarga() { return $this->hasMany(KepalaKeluarga::class); }
}