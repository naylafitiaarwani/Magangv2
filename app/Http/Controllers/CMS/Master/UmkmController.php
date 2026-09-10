<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\UmkmResource;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UmkmController extends Controller
{
    public function index()
    {
        $umkms = Umkm::latest()->paginate(10);

        return view('CMS.umkm.index', compact('umkms'));
    }

    public function create()
    {
        return view('CMS.umkm.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:umkms,nama',
            'nama_pelaku' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'harga_mulai' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kontak' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('umkm', 'public');
        }

        Umkm::create($validated);

        return redirect('/admin/umkm')
            ->with('success', 'Data UMKM berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $umkm = Umkm::findOrFail($id);

        return view('CMS.umkm.edit', compact('umkm'));
    }

    public function update(Request $request, $id)
    {
        $umkm = Umkm::findOrFail($id);

            $validated = $request->validate([
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('umkms', 'nama')->ignore($umkm->id),
                ],
                'nama_pelaku' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'harga_mulai' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kontak' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            $this->deleteGambar($umkm->gambar);

            $validated['gambar'] = $request->file('gambar')
                ->store('umkm', 'public');
        } else {
            unset($validated['gambar']);
        }

        $umkm->update($validated);

        return redirect('/admin/umkm')
            ->with('success', 'Data UMKM berhasil diperbarui.');
    }

    public function delete($id)
    {
        $umkm = Umkm::findOrFail($id);

        $this->deleteGambar($umkm->gambar);

        $umkm->delete();

        return redirect('/admin/umkm')
            ->with('success', 'Data UMKM berhasil dihapus.');
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

        $oldPath = 'umkm/' . basename($gambar);

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }

}