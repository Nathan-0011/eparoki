<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SongNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class SongNumberController extends Controller
{
    /**
     * Endpoint untuk Mobile App mengirim nomor lagu.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'song_number' => ['required', 'string', 'max:4', 'regex:/^[0-9]+$/'],
            'device_id'   => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // 1. Format jadi 4 digit
            $formattedNumber = str_pad($request->song_number, 4, '0', STR_PAD_LEFT);
            $deviceId = $request->device_id ?? 'display-01';

            // 2. Simpan ke DB dengan status pending
            $songNumber = SongNumber::create([
                'song_number' => $formattedNumber,
                'device_id'   => $deviceId,
                'status'      => 'pending',
                'sent_at'     => now(),
            ]);

            // 3. Coba kirim ke IoT device
            $isMock = config('iot.mock_mode', true);

            if ($isMock) {
                Log::warning('IoT device tidak tersedia, mode simulasi aktif (API).');
                $songNumber->update([
                    'status'  => 'sent',
                    'sent_at' => now(),
                ]);
            } else {
                try {
                    $response = Http::timeout(config('iot.device_timeout', 5))->post(
                        config('iot.device_url') . '/api/display',
                        [
                            'number'    => $formattedNumber,
                            'device_id' => $deviceId
                        ]
                    );

                    $songNumber->update([
                        'status'  => $response->successful() ? 'sent' : 'failed',
                        'sent_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    $songNumber->update([
                        'status'  => 'failed',
                        'sent_at' => now(),
                    ]);
                }
            }

            // Return response (meskipun failed di IoT, API tetap success merespon aplikasi mobile)
            return response()->json([
                'success' => true,
                'message' => 'Nomor lagu berhasil diproses',
                'data'    => [
                    'id'          => $songNumber->id,
                    'song_number' => $songNumber->song_number,
                    'device_id'   => $songNumber->device_id,
                    'status'      => $songNumber->status,
                    'sent_at'     => $songNumber->sent_at->toIso8601String(),
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint untuk mendapatkan status nomor lagu terakhir.
     */
    public function latest()
    {
        try {
            $latest = SongNumber::latest('sent_at')->first();

            if (!$latest) {
                return response()->json([
                    'success' => true,
                    'data'    => null,
                    'message' => 'Belum ada nomor lagu yang dikirim',
                ]);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'song_number'   => $latest->song_number,
                    'device_id'     => $latest->device_id,
                    'status'        => $latest->status,
                    'sent_at'       => $latest->sent_at->toIso8601String(),
                    'sent_at_human' => $latest->sent_at->diffForHumans(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}