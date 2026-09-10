<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\ProdukUmkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukUmkmController extends Controller
{
    public function index(Umkm $umkm)
    {
        $produk = $umkm->produk()
            ->latest()
            ->get();

        return view('CMS.produk_umkm.index', compact(
            'umkm',
            'produk'
        ));
    }

    public function create(Umkm $umkm)
    {
        return view('CMS.produk_umkm.create', compact(
            'umkm'
        ));
    }

    public function store(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'harga' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['umkm_id'] = $umkm->id;
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request
                ->file('gambar')
                ->store('produk_umkm', 'public');
        }

        ProdukUmkm::create($validated);

        return redirect()
            ->route('admin.umkm.produk.index', $umkm)
            ->with(
                'success',
                'Produk berhasil ditambahkan.'
            );
    }

    public function edit(Umkm $umkm, ProdukUmkm $produk)
    {
        abort_unless(
            $produk->umkm_id === $umkm->id,
            404
        );

        return view(
            'CMS.produk_umkm.edit',
            compact('umkm', 'produk')
        );
    }

    public function update(
        Request $request,
        Umkm $umkm,
        ProdukUmkm $produk
    ) {
        abort_unless(
            $produk->umkm_id === $umkm->id,
            404
        );

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'harga' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {

            if ($produk->gambar) {
                Storage::disk('public')
                    ->delete($produk->gambar);
            }

            $validated['gambar'] = $request
                ->file('gambar')
                ->store('produk_umkm', 'public');
        }

        $produk->update($validated);

        return redirect()
            ->route('admin.umkm.produk.index', $umkm)
            ->with(
                'success',
                'Produk berhasil diperbarui.'
            );
    }

    public function destroy(
        Umkm $umkm,
        ProdukUmkm $produk
    ) {
        abort_unless(
            $produk->umkm_id === $umkm->id,
            404
        );

        if ($produk->gambar) {
            Storage::disk('public')
                ->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()
            ->route('admin.umkm.produk.index', $umkm)
            ->with(
                'success',
                'Produk berhasil dihapus.'
            );
    }
}