<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sejarah\StoreSejarahRequest;
use App\Http\Requests\Sejarah\UpdateSejarahRequest;
use App\Http\Resources\SejarahResource;
use App\Models\Sejarah;
use Illuminate\Http\Request;

class SejarahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sejarah::with('images')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
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

        return SejarahResource::collection($data);
    }

    public function store(StoreSejarahRequest $request)
    {
        $sejarah = Sejarah::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Sejarah berhasil ditambahkan',
            'data' => new SejarahResource($sejarah),
        ], 201);
    }

    public function show(string $slug)
    {
        $sejarah = Sejarah::with('images')
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail sejarah berhasil diambil',
            'data' => new SejarahResource($sejarah),
        ]);
    }

    public function update(
        UpdateSejarahRequest $request,
        Sejarah $sejarah
    ) {
        $sejarah->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Sejarah berhasil diperbarui',
            'data' => new SejarahResource($sejarah),
        ]);
    }

    public function destroy(Sejarah $sejarah)
    {
        $sejarah->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sejarah berhasil dihapus',
        ]);
    }
}