<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budaya\StoreBudayaRequest;
use App\Http\Requests\Budaya\UpdateBudayaRequest;
use App\Http\Resources\BudayaResource;
use App\Models\Budaya;
use Illuminate\Http\Request;

class BudayaController extends Controller
{
    public function index(Request $request)
    {
        $query = Budaya::query()
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

        return BudayaResource::collection($data);
    }

    public function store(StoreBudayaRequest $request)
    {
        $budaya = Budaya::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Budaya berhasil ditambahkan',
            'data' => new BudayaResource($budaya),
        ], 201);
    }

    public function show(string $slug)
    {
        $budaya = Budaya::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail budaya berhasil diambil',
            'data' => new BudayaResource($budaya),
        ]);
    }

    public function update(
        UpdateBudayaRequest $request,
        Budaya $budaya
    ) {
        $budaya->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Budaya berhasil diperbarui',
            'data' => new BudayaResource($budaya),
        ]);
    }

    public function destroy(Budaya $budaya)
    {
        $budaya->delete();

        return response()->json([
            'success' => true,
            'message' => 'Budaya berhasil dihapus',
        ]);
    }
}