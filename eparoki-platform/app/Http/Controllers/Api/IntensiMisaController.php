<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IntensiMisa;
use App\Http\Resources\IntensiMisaResource;
use App\Helpers\ApiResponse;
use Carbon\Carbon;
use Exception;

class IntensiMisaController extends Controller
{
    /**
     * Mengembalikan daftar intensi misa minggu ini (yang belum diarsipkan).
     * Format response lengkap termasuk meta statistik.
     */
    public function index()
    {
        try {
            $seninMingguIni = Carbon::now()->startOfWeek(Carbon::MONDAY);

            $data = IntensiMisa::where('is_archived', false)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalNominal = $data->sum('amount') ?? 0;

            return response()->json([
                'success' => true,
                'message' => 'Data intensi misa minggu ini',
                'data'    => IntensiMisaResource::collection($data),
                'meta'    => [
                    'total'                   => $data->count(),
                    'week_date'               => $seninMingguIni->toDateString(),
                    'total_nominal'           => $totalNominal,
                    'total_nominal_formatted' => 'Rp ' . number_format($totalNominal, 0, ',', '.'),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }
}