<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParishProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'diocese',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'logo',
        'established_year',
        'feast_day',
        'google_maps_url',
        'google_maps_embed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'established_year' => 'integer',
    ];

    /**
     * Get the singleton profile instance.
     * If it doesn't exist, it will create one with default values.
     *
     * @return \App\Models\ParishProfile
     */
    public static function getProfile(): self
    {
        return self::firstOrCreate(
            ['id' => 1], // Selalu ambil ID 1 karena ini adalah singleton
            [
                'name' => 'Paroki Santo Fidelis Parapat',
                'diocese' => 'Keuskupan Agung Medan',
                'address' => 'Jl. Gereja No.1, Parapat, Kec. Girsang Sipangan Bolon, Kabupaten Simalungun, Sumatera Utara 21174',
                'phone' => '(0625) 41234',
                'email' => 'sekretariat@ekatolik-parapat.id',
                'website' => 'https://ekatolik-parapat.id',
                'description' => 'Paroki Santo Fidelis Parapat adalah sebuah paroki di bawah naungan Keuskupan Agung Medan yang terletak di tepian Danau Toba yang indah.',
                'established_year' => 1952,
                'feast_day' => '24 April',
            ]
        );
    }
}
