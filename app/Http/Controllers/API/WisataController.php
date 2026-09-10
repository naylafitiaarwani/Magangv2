<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wisata\StoreWisataRequest;
use App\Http\Requests\Wisata\UpdateWisataRequest;
use App\Http\Resources\WisataResource;
use App\Models\Wisata;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        $query = Wisata::query()
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

        $wisata = $query
            ->latest()
            ->paginate($request->integer('per_page', 9));

        return WisataResource::collection($wisata);
    }

    public function store(StoreWisataRequest $request)
    {
        $wisata = Wisata::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Wisata berhasil ditambahkan',
            'data' => new WisataResource($wisata),
        ], 201);
    }

    public function show(string $slug)
    {
        $wisata = Wisata::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail wisata berhasil diambil',
            'data' => new WisataResource($wisata),
        ]);
    }

    public function update(
        UpdateWisataRequest $request,
        Wisata $wisata
    ) {
        $wisata->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Wisata berhasil diperbarui',
            'data' => new WisataResource($wisata),
        ]);
    }

    public function destroy(Wisata $wisata)
    {
        $wisata->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wisata berhasil dihapus',
        ]);
    }
}