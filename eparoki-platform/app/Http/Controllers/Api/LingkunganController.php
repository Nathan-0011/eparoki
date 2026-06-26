<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lingkungan;
use Illuminate\Http\Request;
use Exception;

class LingkunganController extends Controller
{
    public function index()
    {
        try {
            $lingkungans = Lingkungan::withCount('kepalaKeluarga')->get();
            $totalJiwa = 0;
            
            $data = $lingkungans->map(function ($lingkungan) use (&$totalJiwa) {
                $jiwa = $lingkungan->kepalaKeluarga()->sum('jumlah_anggota');
                $totalJiwa += $jiwa;
                return [
                    'id' => $lingkungan->id,
                    'name' => $lingkungan->name,
                    'patron_saint' => $lingkungan->patron_saint,
                    'description' => $lingkungan->description,
                    'photo_url' => $lingkungan->photo ? asset('storage/' . $lingkungan->photo) : null,
                    'jumlah_kk' => $lingkungan->kepala_keluarga_count,
                    'jumlah_jiwa' => (int) $jiwa,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Daftar lingkungan paroki',
                'data' => $data,
                'meta' => [
                    'total_lingkungan' => $lingkungans->count(),
                    'total_kk' => $lingkungans->sum('kepala_keluarga_count'),
                    'total_jiwa' => $totalJiwa,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $lingkungan = Lingkungan::withCount('kepalaKeluarga')->findOrFail($id);
            $jiwa = $lingkungan->kepalaKeluarga()->sum('jumlah_anggota');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $lingkungan->id,
                    'name' => $lingkungan->name,
                    'patron_saint' => $lingkungan->patron_saint,
                    'description' => $lingkungan->description,
                    'photo_url' => $lingkungan->photo ? asset('storage/' . $lingkungan->photo) : null,
                    'jumlah_kk' => $lingkungan->kepala_keluarga_count,
                    'jumlah_jiwa' => (int) $jiwa,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
    }

    public function kepalaKeluarga($id)
    {
        try {
            $lingkungan = Lingkungan::findOrFail($id);
            $kks = $lingkungan->kepalaKeluarga;
            $jiwa = $kks->sum('jumlah_anggota');
            
            $data = $kks->map(function ($kk) {
                return [
                    'id' => $kk->id,
                    'nama_kk' => $kk->nama_kk,
                    'alamat' => $kk->alamat,
                    'jumlah_anggota' => $kk->jumlah_anggota,
                    'no_telp' => $kk->no_telp,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'lingkungan' => $lingkungan->name,
                    'total_kk' => $kks->count(),
                    'total_jiwa' => (int) $jiwa,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
    }
}