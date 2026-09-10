<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WisataController extends Controller
{
    public function index()
    {
        $wisatas = Wisata::latest()->paginate(10);

        return view('CMS.wisata.index', compact('wisatas'));
    }

    public function create()
    {
        return view('CMS.wisata.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:wisatas,nama',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'jam_operasional' => 'nullable|string|max:255',
            'harga_mulai' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'jumlah_ulasan' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        $validated['rating'] = $validated['rating'] ?? 0;
        $validated['jumlah_ulasan'] = $validated['jumlah_ulasan'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        /*
        |--------------------------------------------------------------------------
        | Upload gambar
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('wisata', 'public');
        }

        Wisata::create($validated);

        return redirect('/admin/wisata')
            ->with('success', 'Data wisata berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $wisata = Wisata::findOrFail($id);

        return view('CMS.wisata.edit', compact('wisata'));
    }

    public function update(Request $request, $id)
    {
        $wisata = Wisata::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('wisatas', 'nama')->ignore($wisata->id),
            ],
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'jam_operasional' => 'nullable|string|max:255',
            'harga_mulai' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'jumlah_ulasan' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        $validated['rating'] = $validated['rating'] ?? 0;
        $validated['jumlah_ulasan'] = $validated['jumlah_ulasan'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        /*
        |--------------------------------------------------------------------------
        | Jika upload gambar baru
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            $this->deleteGambar($wisata->gambar);

            // Simpan gambar baru
            $validated['gambar'] = $request->file('gambar')
                ->store('wisata', 'public');
        } else {
            // Jangan ubah gambar lama
            unset($validated['gambar']);
        }

        $wisata->update($validated);

        return redirect('/admin/wisata')
            ->with('success', 'Data wisata berhasil diperbarui.');
    }

    public function delete($id)
    {
        $wisata = Wisata::findOrFail($id);

        // Hapus file gambar
        $this->deleteGambar($wisata->gambar);

        // Hapus data database
        $wisata->delete();

        return redirect('/admin/wisata')
            ->with('success', 'Data wisata berhasil dihapus.');
    }

    /**
     * Menghapus gambar dari storage.
     */
    private function deleteGambar($gambar)
    {
        if (!$gambar) {
            return;
        }

        /*
         * Data baru:
         * wisata/nama-file.jpg
         */
        if (Storage::disk('public')->exists($gambar)) {
            Storage::disk('public')->delete($gambar);
            return;
        }

        /*
         * Data lama yang hanya menyimpan:
         * nama-file.jpg
         */
        $oldPath = 'wisata/' . basename($gambar);

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
