<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukUmkmResource;
use App\Models\Umkm;
use App\Models\ProdukUmkm;

class ProdukUmkmController extends Controller
{
    public function index(string $slug)
    {
        $umkm = Umkm::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $produk = $umkm->produk()
            ->where('is_active', true)
            ->latest()
            ->get();

        return ProdukUmkmResource::collection($produk);
    }

    public function show(string $slug, ProdukUmkm $produk)
    {
        $umkm = Umkm::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        abort_unless(
            $produk->umkm_id === $umkm->id &&
            $produk->is_active,
            404
        );

        return response()->json([
            'success' => true,
            'message' => 'Detail produk berhasil diambil',
            'data' => new ProdukUmkmResource($produk),
        ]);
    }
}
