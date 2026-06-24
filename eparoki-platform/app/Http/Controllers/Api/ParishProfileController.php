<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParishProfile;
use App\Models\Pastor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParishProfileController extends Controller
{
    /**
     * Mengambil data profil paroki lengkap dengan pastor aktif
     */
    public function index()
    {
        try {
            $profile = ParishProfile::getProfile();
            $pastorAktif = Pastor::active()->first();

            // Format URL gambar agar valid (absolute URL)
            $logoUrl = $profile->logo ? Storage::url($profile->logo) : null;
            if ($logoUrl && !str_starts_with($logoUrl, 'http')) {
                $logoUrl = url($logoUrl);
            }

            $pastorData = null;
            if ($pastorAktif) {
                $pastorPhotoUrl = $pastorAktif->photo ? Storage::url($pastorAktif->photo) : null;
                if ($pastorPhotoUrl && !str_starts_with($pastorPhotoUrl, 'http')) {
                    $pastorPhotoUrl = url($pastorPhotoUrl);
                }

                $pastorData = [
                    'name' => $pastorAktif->name,
                    'photo_url' => $pastorPhotoUrl,
                    'period_start' => $pastorAktif->period_start,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Data profil paroki berhasil diambil',
                'data' => [
                    'name' => $profile->name,
                    'diocese' => $profile->diocese,
                    'address' => $profile->address,
                    'phone' => $profile->phone,
                    'email' => $profile->email,
                    'website' => $profile->website,
                    'description' => $profile->description,
                    'established_year' => $profile->established_year,
                    'feast_day' => $profile->feast_day,
                    'logo_url' => $logoUrl,
                    'google_maps_url' => $profile->google_maps_url,
                    'pastor_aktif' => $pastorData,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}
