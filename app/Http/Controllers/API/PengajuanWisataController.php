<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengajuanWisata\StorePengajuanWisataRequest;
use App\Http\Requests\PengajuanWisata\UpdateStatusPengajuanWisataRequest;
use App\Http\Resources\PengajuanWisataResource;
use App\Models\PengajuanWisata;
use App\Models\Wisata;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanWisataController extends Controller
{
    /**
     * Menampilkan daftar pengajuan wisata.
     */
    public function index(Request $request)
    {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $query = PengajuanWisata::where('user_id', $userData->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuan = $query->paginate(
            $request->integer('per_page', 10)
        );

        return response()->json([
            'success' => true,
            'message' => 'Data pengajuan wisata berhasil diambil.',
            'data' => PengajuanWisataResource::collection($pengajuan),
            'pagination' => [
                'current_page' => $pengajuan->currentPage(),
                'last_page' => $pengajuan->lastPage(),
                'per_page' => $pengajuan->perPage(),
                'total' => $pengajuan->total(),
            ],
        ], 200);
    }

    /**
     * Menampilkan detail satu pengajuan wisata.
     */
    public function show(Request $request, PengajuanWisata $pengajuanWisata)
    {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $isAdmin = in_array(
            (int) ($userData->role_id ?? 0),
            [1, 2],
            true
        );

        if (!$isAdmin && $pengajuanWisata->user_id != $userData->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pengajuan ini.',
            ], 403);
        }

        $pengajuanWisata->load('wisata');

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan wisata berhasil diambil.',
            'data' => new PengajuanWisataResource($pengajuanWisata),
        ], 200);
    }

    /**
     * Menyimpan pengajuan wisata baru.
     */
    public function store(StorePengajuanWisataRequest $request): JsonResponse
    {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | User ID dari JWT
        |--------------------------------------------------------------------------
        |
        | CheckAuthFrontend sudah memvalidasi token JWT.
        | Data user yang disimpan oleh middleware diambil dari request attributes.
        |
        */

        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $data['user_id'] = $userData->id;

        /*
        |--------------------------------------------------------------------------
        | Upload foto lokasi wisata
        |--------------------------------------------------------------------------
        */

        $fotoLokasi = [];

        if ($request->hasFile('foto_lokasi_wisata')) {
            foreach ($request->file('foto_lokasi_wisata') as $file) {
                $fotoLokasi[] = $file->store(
                    'pengajuan/wisata/lokasi',
                    'public'
                );
            }
        }

        $data['foto_lokasi_wisata'] = $fotoLokasi;

        /*
        |--------------------------------------------------------------------------
        | Upload berdasarkan jenis entitas
        |--------------------------------------------------------------------------
        */

        if ($request->status_entitas === 'perorangan') {

            /*
            |----------------------------------------------------------------------
            | Upload KTP
            |----------------------------------------------------------------------
            */

            if ($request->hasFile('foto_ktp')) {
                $data['foto_ktp'] = $request->file('foto_ktp')
                    ->store('pengajuan/wisata/ktp', 'public');
            }

            /*
            |----------------------------------------------------------------------
            | Upload selfie
            |----------------------------------------------------------------------
            */

            if ($request->hasFile('foto_selfie')) {
                $data['foto_selfie'] = $request->file('foto_selfie')
                    ->store('pengajuan/wisata/selfie', 'public');
            }

            // Field organisasi tidak diperlukan
            $data['nama_organisasi'] = null;
            $data['dokumen_nib'] = null;
            $data['active_online_tiket'] = null;

        } else {

            /*
            |----------------------------------------------------------------------
            | Upload NIB
            |----------------------------------------------------------------------
            */

            $dokumenNib = [];

            if ($request->hasFile('dokumen_nib')) {
                foreach ($request->file('dokumen_nib') as $file) {
                    $dokumenNib[] = $file->store(
                        'pengajuan/wisata/nib',
                        'public'
                    );
                }
            }

            $data['dokumen_nib'] = $dokumenNib;

            // Field perorangan tidak diperlukan
            $data['foto_ktp'] = null;
            $data['foto_selfie'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Status awal
        |--------------------------------------------------------------------------
        */

        $data['status'] = 'pending';
        $data['alasan_penolakan'] = null;

        /*
        |--------------------------------------------------------------------------
        | Simpan pengajuan
        |--------------------------------------------------------------------------
        */

        $pengajuan = PengajuanWisata::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan wisata berhasil dikirim.',
            'data' => new PengajuanWisataResource($pengajuan),
        ], 201);
    }

        /**
     * Mengubah status pengajuan.
     */
    public function updateStatus(
        UpdateStatusPengajuanWisataRequest $request,
        PengajuanWisata $pengajuanWisata
    ) {
        $data = $request->validated();

        // Pengajuan yang sudah diproses tidak boleh diproses ulang
        if ($pengajuanWisata->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan yang sudah diproses tidak dapat diubah lagi.',
            ], 400);
        }

        DB::beginTransaction();

        try {

            // =====================================================
            // APPROVED
            // =====================================================

            if ($data['status'] === 'approved') {

                $gambar = null;

                if (
                    is_array($pengajuanWisata->foto_lokasi_wisata) &&
                    count($pengajuanWisata->foto_lokasi_wisata) > 0
                ) {
                    $gambar = $pengajuanWisata->foto_lokasi_wisata[0];
                }

                // Pelaku wisata memilih mengaktifkan penjualan tiket online
                // lewat kolom `active_online_tiket` saat mengajukan (khusus
                // entitas organisasi). Nilainya dipetakan ke flag boolean
                // `is_tiket_aktif` pada tabel wisatas.
                $isTiketAktif = strtolower((string) $pengajuanWisata->active_online_tiket) === 'aktif';

                $wisata = Wisata::create([
                    'user_id' => $pengajuanWisata->user_id,
                    'nama' => $pengajuanWisata->nama_wisata,
                    'deskripsi' => $pengajuanWisata->deskripsi_wisata,
                    'lokasi' => $pengajuanWisata->alamat_wisata,
                    'gambar' => $gambar,
                    'jam_operasional' => null,
                    'harga_mulai' => null,
                    'rating' => 0,
                    'jumlah_ulasan' => 0,
                    'kategori' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'is_active' => true,
                    'is_tiket_aktif' => $isTiketAktif,
                ]);

                $pengajuanWisata->status = 'approved';
                $pengajuanWisata->wisata_id = $wisata->id;
                $pengajuanWisata->alasan_penolakan = null;
                $pengajuanWisata->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan wisata berhasil disetujui dan menjadi data wisata.',
                    'data' => [
                        'pengajuan' => new PengajuanWisataResource($pengajuanWisata),
                        'wisata' => $wisata,
                    ],
                ], 200);
            }


            // =====================================================
            // REJECTED
            // =====================================================

            if ($data['status'] === 'rejected') {

                $pengajuanWisata->status = 'rejected';
                $pengajuanWisata->alasan_penolakan =
                    $data['alasan_penolakan'] ?? null;

                $pengajuanWisata->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan wisata berhasil ditolak.',
                    'data' => new PengajuanWisataResource($pengajuanWisata),
                ], 200);
            }


            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid.',
            ], 400);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengajuan wisata.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menghapus pengajuan.
     */
    public function destroy(PengajuanWisata $pengajuanWisata)
    {
        // Hapus file foto lokasi
        if (is_array($pengajuanWisata->foto_lokasi_wisata)) {
            foreach ($pengajuanWisata->foto_lokasi_wisata as $file) {
                $path = public_path($file);

                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        // Hapus file KTP
        if ($pengajuanWisata->foto_ktp) {
            $path = public_path($pengajuanWisata->foto_ktp);

            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Hapus file selfie
        if ($pengajuanWisata->foto_selfie) {
            $path = public_path($pengajuanWisata->foto_selfie);

            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Hapus dokumen NIB
        if (is_array($pengajuanWisata->dokumen_nib)) {
            foreach ($pengajuanWisata->dokumen_nib as $file) {
                $path = public_path($file);

                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        $pengajuanWisata->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan wisata berhasil dihapus.',
        ], 200);
    }
}
