<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    /**
     * Mengembalikan daftar banner aktif untuk carousel aplikasi mobile.
     */
    public function index(): JsonResponse
    {
        try {
            $banners = Banner::active()->get();
            
            $formattedData = $banners->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'image_url' => $banner->image_url,
                    'order' => $banner->order,
                    'start_date' => $banner->start_date ? $banner->start_date->toDateString() : null,
                    'end_date' => $banner->end_date ? $banner->end_date->toDateString() : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data banner kegiatan',
                'data' => $formattedData,
                'meta' => [
                    'total' => $banners->count(),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}