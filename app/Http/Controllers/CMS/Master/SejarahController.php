<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\Sejarah;
use App\Models\SejarahImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SejarahController extends Controller
{
    public function index()
    {
        $sejarahs = Sejarah::latest()->paginate(10);

        return view('CMS.sejarah.index', compact('sejarahs'));
    }

    public function create()
    {
        return view('CMS.sejarah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255|unique:sejarahs,judul',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',

            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_active' => 'nullable|boolean',
        ]);

        $sejarah = Sejarah::create([
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'deskripsi' => $validated['deskripsi'] ?? null,
            'lokasi' => $validated['lokasi'] ?? null,
            'kategori' => $validated['kategori'] ?? null,
            'is_active' => $request->has('is_active'),

            // Gambar utama lama tetap dikosongkan.
            // Semua gambar baru disimpan di sejarah_images.
            'gambar' => null,
        ]);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $index => $file) {
                $path = $file->store('sejarah', 'public');

                SejarahImage::create([
                    'sejarah_id' => $sejarah->id,
                    'gambar' => $path,
                    'urutan' => $index + 1,
                ]);
            }
        }

        return redirect('/admin/sejarah')
            ->with('success', 'Data sejarah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sejarah = Sejarah::with('images')
            ->findOrFail($id);

        return view('CMS.sejarah.edit', compact('sejarah'));
    }

    public function update(Request $request, $id)
    {
        $sejarah = Sejarah::with('images')
            ->findOrFail($id);

        $validated = $request->validate([
            'judul' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sejarahs', 'judul')->ignore($sejarah->id),
            ],

            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',

            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'hapus_gambar' => 'nullable|array',
            'hapus_gambar.*' => 'integer|exists:sejarah_images,id',

            'is_active' => 'nullable|boolean',
        ]);

        $sejarah->update([
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'deskripsi' => $validated['deskripsi'] ?? null,
            'lokasi' => $validated['lokasi'] ?? null,
            'kategori' => $validated['kategori'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | HAPUS GAMBAR YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hapus_gambar')) {

            $images = SejarahImage::where('sejarah_id', $sejarah->id)
                ->whereIn('id', $request->hapus_gambar)
                ->get();

            foreach ($images as $image) {

                $this->deleteGambar($image->gambar);

                $image->delete();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TAMBAH GAMBAR BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {

            $lastOrder = SejarahImage::where('sejarah_id', $sejarah->id)
                ->max('urutan') ?? 0;

            foreach ($request->file('gambar') as $index => $file) {

                $path = $file->store('sejarah', 'public');

                SejarahImage::create([
                    'sejarah_id' => $sejarah->id,
                    'gambar' => $path,
                    'urutan' => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect('/admin/sejarah')
            ->with('success', 'Data sejarah berhasil diperbarui.');
    }

    public function delete($id)
    {
        $sejarah = Sejarah::with('images')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | HAPUS GAMBAR LAMA
        |--------------------------------------------------------------------------
        */

        $this->deleteGambar($sejarah->gambar);

        /*
        |--------------------------------------------------------------------------
        | HAPUS SEMUA GAMBAR TAMBAHAN
        |--------------------------------------------------------------------------
        */

        foreach ($sejarah->images as $image) {

            $this->deleteGambar($image->gambar);

            $image->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA SEJARAH
        |--------------------------------------------------------------------------
        */

        $sejarah->delete();

        return redirect('/admin/sejarah')
            ->with('success', 'Data sejarah berhasil dihapus.');
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

        $oldPath = 'sejarah/' . basename($gambar);

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
