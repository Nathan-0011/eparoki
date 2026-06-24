<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Resources\BannerResource;
use App\Helpers\ApiResponse;
use Exception;

class BannerController extends Controller {
    public function index() {
        try {
            $data = Banner::active()->orderBy("order")->get();
            return ApiResponse::success(BannerResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}