<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\JadwalIbadah;
use App\Http\Resources\JadwalIbadahResource;
use App\Helpers\ApiResponse;
use Exception;

class JadwalIbadahController extends Controller {
    public function index($calendar_id) {
        try {
            $data = JadwalIbadah::where("calendar_id", $calendar_id)->get();
            return ApiResponse::success(JadwalIbadahResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}