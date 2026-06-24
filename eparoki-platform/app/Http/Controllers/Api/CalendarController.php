<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\LiturgicalCalendar;
use App\Http\Resources\LiturgicalCalendarResource;
use App\Helpers\ApiResponse;
use Exception;

class CalendarController extends Controller {
    public function index($year, $month) {
        try {
            $data = LiturgicalCalendar::byYearMonth($year, $month)->get();
            return ApiResponse::success(LiturgicalCalendarResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function show($year, $month, $week) {
        try {
            $data = LiturgicalCalendar::where("year", $year)->where("month", $month)->where("week_number", $week)->with("jadwalIbadah")->first();
            if (!$data) return ApiResponse::error("Tidak ditemukan", null, 404);
            return ApiResponse::success(new LiturgicalCalendarResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}