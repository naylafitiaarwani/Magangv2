<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\PengajuanWisata;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PengajuanWisataController extends Controller
{
    /**
     * Daftar pengajuan wisata (bisa difilter lewat ?status=pending|approved|rejected)
     */
    public function index(Request $request)
    {
        $query = PengajuanWisata::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->paginate(10)->withQueryString();

        return view('CMS.pengajuan-wisata.index', compact('pengajuans'));
    }

    /**
     * Approve atau reject satu pengajuan.
     * Route sengaja dinamai "update-status" (bukan "approve"/"reject")
     * supaya cocok dengan pengecekan privilege "edit" di CheckPriviledge
     * middleware (dia mendeteksi keyword "update" di path).
     */
    public function updateStatus(Request $request, $id)
    {
        $pengajuan = PengajuanWisata::findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return redirect('/admin/pengajuan-wisata')
                ->with('error', 'Pengajuan ini sudah pernah diproses.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'alasan_penolakan' => 'required_if:action,reject|nullable|string',
        ]);

        DB::beginTransaction();

        try {
            if ($request->action === 'approve') {

                $gambar = null;

                if (
                    is_array($pengajuan->foto_lokasi_wisata) &&
                    count($pengajuan->foto_lokasi_wisata) > 0
                ) {
                    $gambar = $pengajuan->foto_lokasi_wisata[0];
                }

                $isTiketAktif = strtolower((string) $pengajuan->active_online_tiket) === 'aktif';

                $wisata = Wisata::create([
                    'user_id' => $pengajuan->user_id,
                    'nama' => $pengajuan->nama_wisata,
                    'deskripsi' => $pengajuan->deskripsi_wisata,
                    'lokasi' => $pengajuan->alamat_wisata,
                    'gambar' => $gambar,
                    'rating' => 0,
                    'jumlah_ulasan' => 0,
                    'is_active' => true,
                    'is_tiket_aktif' => $isTiketAktif,
                ]);

                $pengajuan->status = 'approved';
                $pengajuan->wisata_id = $wisata->id;
                $pengajuan->alasan_penolakan = null;
                $pengajuan->save();

                DB::commit();

                return redirect('/admin/pengajuan-wisata')
                    ->with('success', 'Pengajuan disetujui, data wisata otomatis dibuat.');
            }

            // action === reject
            $pengajuan->status = 'rejected';
            $pengajuan->alasan_penolakan = $request->alasan_penolakan;
            $pengajuan->save();

            DB::commit();

            return redirect('/admin/pengajuan-wisata')
                ->with('success', 'Pengajuan berhasil ditolak.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect('/admin/pengajuan-wisata')
                ->with('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
        }
    }
}
