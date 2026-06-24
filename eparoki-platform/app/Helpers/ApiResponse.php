<?php
namespace App\Helpers;

class ApiResponse {
    public static function success($data = null, $message = "Data berhasil diambil", $meta = null, $code = 200) {
        $response = ["success" => true, "message" => $message, "data" => $data];
        if ($meta) { $response["meta"] = $meta; }
        return response()->json($response, $code);
    }
    public static function error($message = "Terjadi kesalahan", $errors = null, $code = 400) {
        $response = ["success" => false, "message" => $message];
        if ($errors) { $response["errors"] = $errors; }
        return response()->json($response, $code);
    }
}