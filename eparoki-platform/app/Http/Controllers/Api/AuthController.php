<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmatUser;
use App\Helpers\ApiResponse;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller {
    public function googleLogin(Request $request) {
        try {
            $request->validate(["token" => "required"]);
            // Untuk validasi token dari mobile, gunakan library google-auth atau request manual
            // Contoh simpel jika menggunakan socialite (biasanya untuk web):
            // $googleUser = Socialite::driver("google")->userFromToken($request->token);
            
            // Simulasi data untuk keperluan test:
            $email = "umat@example.com";
            $name = "Umat User";
            $google_id = "123456789";
            
            $user = UmatUser::updateOrCreate(
                ["email" => $email],
                ["name" => $name, "google_id" => $google_id, "avatar" => ""]
            );
            $token = $user->createToken("umat-token")->plainTextToken;
            return ApiResponse::success(["user" => $user, "token" => $token], "Login sukses");
        } catch (Exception $e) {
            return ApiResponse::error("Login gagal: " . $e->getMessage(), null, 500);
        }
    }
    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            return ApiResponse::success(null, "Logout berhasil");
        } catch (Exception $e) {
            return ApiResponse::error("Logout gagal", null, 500);
        }
    }
}