<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\JadwalIbadahController;
use App\Http\Controllers\Api\LingkunganController;
use App\Http\Controllers\Api\PastorController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\IntensiMisaController;
use App\Http\Controllers\Api\SongNumberController;
use App\Http\Controllers\Api\ParishProfileController;

// Public routes
Route::get("/profil-paroki", [ParishProfileController::class, "index"]);
Route::get("/banners", [BannerController::class, "index"]);
Route::get("/calendar/{year}/{month}", [CalendarController::class, "index"]);
Route::get("/calendar/{year}/{month}/{week}", [CalendarController::class, "show"]);
Route::get("/jadwal/{calendar_id}", [JadwalIbadahController::class, "index"]);
Route::get("/lingkungan", [LingkunganController::class, "index"]);
Route::get("/lingkungan/{id}", [LingkunganController::class, "show"]);
Route::get("/lingkungan/{id}/kk", [LingkunganController::class, "kepalakeluarga"]);
Route::get("/pastors", [PastorController::class, "index"]);
Route::get("/pastors/active", [PastorController::class, "active"]);
Route::get("/pastors/{id}", [PastorController::class, "show"]);
Route::get("/intensi-misa", [IntensiMisaController::class, "index"]);
Route::get("/song-number/latest", [SongNumberController::class, "latest"]);

// Auth public
Route::post("/auth/google", [AuthController::class, "googleLogin"]);

// Protected
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/auth/logout", [AuthController::class, "logout"]);
    Route::post("/song-number", [SongNumberController::class, "store"]);
});
