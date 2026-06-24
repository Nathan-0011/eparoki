<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Lingkungan;
use App\Models\KepalaKeluarga;
use App\Http\Resources\LingkunganResource;
use App\Http\Resources\KepalaKeluargaResource;
use App\Helpers\ApiResponse;
use Exception;

class LingkunganController extends Controller {
    public function index() {
        try {
            $data = Lingkungan::all();
            return ApiResponse::success(LingkunganResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function show($id) {
        try {
            $data = Lingkungan::find($id);
            if (!$data) return ApiResponse::error("Tidak ditemukan", null, 404);
            return ApiResponse::success(new LingkunganResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function kepalakeluarga($id) {
        try {
            $data = KepalaKeluarga::where("lingkungan_id", $id)->get();
            return ApiResponse::success(KepalaKeluargaResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}