<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kuliner\StoreKulinerRequest;
use App\Http\Requests\Kuliner\UpdateKulinerRequest;
use App\Http\Resources\KulinerResource;
use App\Models\Kuliner;
use Illuminate\Http\Request;

class KulinerController extends Controller
{
    public function index(Request $request)
    {
        $query = Kuliner::query()
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

        return KulinerResource::collection($data);
    }

    public function store(StoreKulinerRequest $request)
    {
        $kuliner = Kuliner::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kuliner berhasil ditambahkan',
            'data' => new KulinerResource($kuliner),
        ], 201);
    }

    public function show(string $slug)
    {
        $kuliner = Kuliner::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail kuliner berhasil diambil',
            'data' => new KulinerResource($kuliner),
        ]);
    }

    public function update(
        UpdateKulinerRequest $request,
        Kuliner $kuliner
    ) {
        $kuliner->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kuliner berhasil diperbarui',
            'data' => new KulinerResource($kuliner),
        ]);
    }

    public function destroy(Kuliner $kuliner)
    {
        $kuliner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kuliner berhasil dihapus',
        ]);
    }
}