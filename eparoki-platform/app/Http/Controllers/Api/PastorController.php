<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pastor;
use App\Http\Resources\PastorResource;
use App\Helpers\ApiResponse;
use Exception;

class PastorController extends Controller {
    public function index() {
        try {
            $data = Pastor::orderBy("period_start", "desc")->get();
            return ApiResponse::success(PastorResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function show($id) {
        try {
            $data = Pastor::with("bphMembers")->find($id);
            if (!$data) return ApiResponse::error("Tidak ditemukan", null, 404);
            return ApiResponse::success(new PastorResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function active() {
        try {
            $data = Pastor::active()->with("bphMembers")->first();
            if (!$data) return ApiResponse::error("Tidak ada pastor aktif", null, 404);
            return ApiResponse::success(new PastorResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}