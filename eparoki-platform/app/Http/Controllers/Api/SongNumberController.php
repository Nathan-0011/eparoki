<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SongNumber;
use App\Http\Resources\SongNumberResource;
use App\Helpers\ApiResponse;
use Exception;

class SongNumberController extends Controller {
    public function latest() {
        try {
            $data = SongNumber::latest()->first();
            if (!$data) return ApiResponse::error("Belum ada lagu", null, 404);
            return ApiResponse::success(new SongNumberResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function store(Request $request) {
        try {
            $request->validate(["song_number" => "required|string|max:4"]);
            $song = SongNumber::create([
                "song_number" => $request->song_number,
                "sent_at" => now(),
                "status" => "sent"
            ]);
            // (Logika publish ke MQTT bisa ditambahkan di sini)
            
            return ApiResponse::success(new SongNumberResource($song), "Lagu dikirim");
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}