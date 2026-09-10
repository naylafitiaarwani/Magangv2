<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\Budaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BudayaController extends Controller
{
    public function index()
    {
        $budayas = Budaya::latest()->paginate(10);

        return view('CMS.budaya.index', compact('budayas'));
    }

    public function create()
    {
        return view('CMS.budaya.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:budayas,nama',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('budaya', 'public');
        }

        Budaya::create($validated);

        return redirect('/admin/budaya')
            ->with('success', 'Data budaya berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $budaya = Budaya::findOrFail($id);

        return view('CMS.budaya.edit', compact('budaya'));
    }

    public function update(Request $request, $id)
    {
        $budaya = Budaya::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('budayas', 'nama')->ignore($budaya->id),
            ],
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            $this->deleteGambar($budaya->gambar);

            $validated['gambar'] = $request->file('gambar')
                ->store('budaya', 'public');
        } else {
            unset($validated['gambar']);
        }

        $budaya->update($validated);

        return redirect('/admin/budaya')
            ->with('success', 'Data budaya berhasil diperbarui.');
    }

    public function delete($id)
    {
        $budaya = Budaya::findOrFail($id);

        $this->deleteGambar($budaya->gambar);

        $budaya->delete();

        return redirect('/admin/budaya')
            ->with('success', 'Data budaya berhasil dihapus.');
    }

    private function deleteGambar($gambar)
    {
        if (!$gambar) {
            return;
        }

        if (Storage::disk('public')->exists($gambar)) {
            Storage::disk('public')->delete($gambar);
            return;
        }

        $oldPath = 'budaya/' . basename($gambar);

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
