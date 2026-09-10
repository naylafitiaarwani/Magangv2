<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KulinerController extends Controller
{
    public function index()
    {
        $kuliners = Kuliner::latest()->paginate(10);

        return view('CMS.kuliner.index', compact('kuliners'));
    }

    public function create()
    {
        return view('CMS.kuliner.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kuliners,nama',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:255',
            'harga_mulai' => 'nullable|numeric|min:0',
            'lokasi' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('kuliner', 'public');
        }

        Kuliner::create($validated);

        return redirect('/admin/kuliner')
            ->with('success', 'Data kuliner berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kuliner = Kuliner::findOrFail($id);

        return view('CMS.kuliner.edit', compact('kuliner'));
    }

    public function update(Request $request, $id)
    {
        $kuliner = Kuliner::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kuliners', 'nama')->ignore($kuliner->id),
            ],
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:255',
            'harga_mulai' => 'nullable|numeric|min:0',
            'lokasi' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            $this->deleteGambar($kuliner->gambar);

            $validated['gambar'] = $request->file('gambar')
                ->store('kuliner', 'public');
        } else {
            unset($validated['gambar']);
        }

        $kuliner->update($validated);

        return redirect('/admin/kuliner')
            ->with('success', 'Data kuliner berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kuliner = Kuliner::findOrFail($id);

        $this->deleteGambar($kuliner->gambar);

        $kuliner->delete();

        return redirect('/admin/kuliner')
            ->with('success', 'Data kuliner berhasil dihapus.');
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

        $oldPath = 'kuliner/' . basename($gambar);

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}