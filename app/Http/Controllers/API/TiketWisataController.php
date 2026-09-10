<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TiketWisata\StoreTiketWisataRequest;
use App\Http\Requests\TiketWisata\UpdateTiketWisataRequest;
use App\Http\Resources\TiketWisataResource;
use App\Models\TiketWisata;
use App\Models\Wisata;
use Illuminate\Http\Request;

class TiketWisataController extends Controller
{
    /**
     * Daftar jenis tiket aktif milik satu wisata (endpoint publik).
     */
    public function index(string $slug)
    {
        $wisata = Wisata::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $tiket = $wisata->tiketWisata()
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tiket berhasil diambil',
            'data' => TiketWisataResource::collection($tiket),
        ]);
    }

    /**
     * Menambah jenis tiket baru (khusus pengelola wisata / admin).
     */
    public function store(StoreTiketWisataRequest $request, Wisata $wisata)
    {
        $forbidden = $this->cekAksesPengelola($request, $wisata);

        if ($forbidden) {
            return $forbidden;
        }

        if (!$wisata->is_tiket_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata ini belum mengaktifkan penjualan tiket online.',
            ], 400);
        }

        $tiket = $wisata->tiketWisata()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jenis tiket berhasil ditambahkan',
            'data' => new TiketWisataResource($tiket),
        ], 201);
    }

    /**
     * Memperbarui jenis tiket (khusus pengelola wisata / admin).
     */
    public function update(
        UpdateTiketWisataRequest $request,
        Wisata $wisata,
        TiketWisata $tiket
    ) {
        $forbidden = $this->cekAksesPengelola($request, $wisata);

        if ($forbidden) {
            return $forbidden;
        }

        if ($tiket->wisata_id !== $wisata->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan untuk wisata ini.',
            ], 404);
        }

        $tiket->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jenis tiket berhasil diperbarui',
            'data' => new TiketWisataResource($tiket),
        ]);
    }

    /**
     * Menghapus jenis tiket (khusus pengelola wisata / admin).
     */
    public function destroy(Request $request, Wisata $wisata, TiketWisata $tiket)
    {
        $forbidden = $this->cekAksesPengelola($request, $wisata);

        if ($forbidden) {
            return $forbidden;
        }

        if ($tiket->wisata_id !== $wisata->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan untuk wisata ini.',
            ], 404);
        }

        $tiket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis tiket berhasil dihapus',
        ]);
    }

    /**
     * Memastikan user yang login adalah pengelola wisata ini atau admin (role 1/2).
     * Mengembalikan JsonResponse kalau ditolak, atau null kalau boleh lanjut.
     */
    private function cekAksesPengelola(Request $request, Wisata $wisata)
    {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $isAdmin = in_array((int) ($userData->role_id ?? 0), [1, 2], true);

        if (!$isAdmin && $wisata->user_id != $userData->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses mengelola tiket wisata ini.',
            ], 403);
        }

        return null;
    }
}
