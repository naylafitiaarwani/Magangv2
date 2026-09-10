<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Umkm\StoreUmkmRequest;
use App\Http\Requests\Umkm\UpdateUmkmRequest;
use App\Http\Resources\UmkmResource;
use App\Models\Umkm;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::query()
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $data = $query
            ->latest()
            ->paginate($request->integer('per_page', 9));

        return UmkmResource::collection($data);
    }

    public function store(StoreUmkmRequest $request)
    {
        $umkm = Umkm::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'UMKM berhasil ditambahkan',
            'data' => new UmkmResource($umkm),
        ], 201);
    }

    public function show(string $slug)
    {
        $umkm = Umkm::with([
            'produk' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->latest();
            }
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail UMKM berhasil diambil',
            'data' => new UmkmResource($umkm),
        ]);
    }

    public function update(
        UpdateUmkmRequest $request,
        Umkm $umkm
    ) {
        $umkm->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'UMKM berhasil diperbarui',
            'data' => new UmkmResource($umkm),
        ]);
    }

    public function destroy(Umkm $umkm)
    {
        $umkm->delete();

        return response()->json([
            'success' => true,
            'message' => 'UMKM berhasil dihapus',
        ]);
    }
}
