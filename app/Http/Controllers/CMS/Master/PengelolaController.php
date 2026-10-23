<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\PesananTiket;
use App\Models\TiketWisata;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PengelolaController extends Controller
{
    /**
     * Halaman utama: daftar wisata milik user login, jenis tiketnya,
     * dan pesanan yang masuk. Diakses siapa saja yang login ke CMS
     * (tidak lewat CheckPriviledge) karena datanya sendiri sudah
     * dibatasi lewat kepemilikan (wisata.user_id).
     */
    public function index(Request $request)
    {
        $userId = Session::get('user')['id'];

        $wisataList = Wisata::where('user_id', $userId)->get();

        $selectedWisata = null;
        $tiketList = collect();
        $pesananList = collect();

        if ($wisataList->count() > 0) {
            $selectedId = $request->integer('wisata_id') ?: $wisataList->first()->id;

            $selectedWisata = $wisataList->firstWhere('id', $selectedId)
                ?? $wisataList->first();

            $tiketList = TiketWisata::where('wisata_id', $selectedWisata->id)
                ->latest()
                ->get();

            $pesananList = PesananTiket::where('wisata_id', $selectedWisata->id)
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        return view('CMS.pengelola.index', compact(
            'wisataList',
            'selectedWisata',
            'tiketList',
            'pesananList'
        ));
    }

    /**
     * Memastikan wisata yang diakses memang milik user yang sedang login.
     */
    private function authorizeWisata(Wisata $wisata): void
    {
        $userId = Session::get('user')['id'];

        if ($wisata->user_id != $userId) {
            abort(403, 'Anda tidak memiliki akses ke wisata ini.');
        }
    }

    public function storeTiket(Request $request)
    {
        $wisata = Wisata::findOrFail($request->wisata_id);
        $this->authorizeWisata($wisata);

        if (!$wisata->is_tiket_aktif) {
            return redirect()->back()
                ->with('error', 'Wisata ini belum mengaktifkan penjualan tiket online.');
        }

        $validated = $request->validate([
            'nama_tiket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['wisata_id'] = $wisata->id;
        $validated['is_active'] = true;

        TiketWisata::create($validated);

        return redirect('/pengelola?wisata_id=' . $wisata->id)
            ->with('success', 'Jenis tiket berhasil ditambahkan.');
    }

    public function updateTiket(Request $request, $id)
    {
        $tiket = TiketWisata::findOrFail($id);
        $this->authorizeWisata($tiket->wisata);

        $validated = $request->validate([
            'nama_tiket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $tiket->update($validated);

        return redirect('/pengelola?wisata_id=' . $tiket->wisata_id)
            ->with('success', 'Jenis tiket berhasil diperbarui.');
    }

    public function deleteTiket($id)
    {
        $tiket = TiketWisata::findOrFail($id);
        $this->authorizeWisata($tiket->wisata);

        $wisataId = $tiket->wisata_id;
        $tiket->delete();

        return redirect('/pengelola?wisata_id=' . $wisataId)
            ->with('success', 'Jenis tiket berhasil dihapus.');
    }

    /**
     * Edit detail wisata milik sendiri (nama, deskripsi, lokasi, dst).
     * Field is_active & is_tiket_aktif SENGAJA tidak bisa diubah dari sini
     * — itu tetap kewenangan admin lewat /admin/wisata.
     */
    public function updateWisata(Request $request, $id)
    {
        $wisata = Wisata::findOrFail($id);
        $this->authorizeWisata($wisata);

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
            'kategori' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        if ($request->hasFile('gambar')) {
            if ($wisata->gambar && Storage::disk('public')->exists($wisata->gambar)) {
                Storage::disk('public')->delete($wisata->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('wisata', 'public');
        }

        $wisata->update($validated);

        return redirect('/pengelola?wisata_id=' . $wisata->id)
            ->with('success', 'Info wisata berhasil diperbarui.');
    }
}
