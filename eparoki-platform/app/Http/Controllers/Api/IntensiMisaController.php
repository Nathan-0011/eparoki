<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\IntensiMisa;
use App\Http\Resources\IntensiMisaResource;
use App\Helpers\ApiResponse;
use Exception;

class IntensiMisaController extends Controller {
    public function index() {
        try {
            $data = IntensiMisa::currentWeek()->get();
            return ApiResponse::success(IntensiMisaResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}