<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransCallbackController extends Controller
{
    public function __invoke(Request $request, MidtransService $midtransService)
    {
        try {
            $pesanan = $midtransService->handleNotification();

            if (!$pesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil diproses.',
            ]);

        } catch (\Throwable $e) {
            // Tetap balas 200 area error tervalidasi hanya untuk kasus signature
            // tidak valid dsb supaya Midtrans tidak mencoba retry berulang;
            // untuk kegagalan lain kirim 500 agar Midtrans retry.
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses notifikasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
