<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengajuanUmkm\StorePengajuanUmkmRequest;
use App\Http\Requests\PengajuanUmkm\UpdateStatusPengajuanUmkmRequest;
use App\Http\Resources\PengajuanUmkmResource;
use App\Models\PengajuanUmkm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Umkm;
use Illuminate\Support\Facades\DB;

class PengajuanUmkmController extends Controller
{
    /**
     * Menampilkan daftar pengajuan UMKM.
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

        $query = PengajuanUmkm::where('user_id', $userData->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuan = $query->paginate(
            $request->integer('per_page', 10)
        );

        return response()->json([
            'success' => true,
            'message' => 'Data pengajuan UMKM berhasil diambil.',
            'data' => PengajuanUmkmResource::collection($pengajuan),
            'pagination' => [
                'current_page' => $pengajuan->currentPage(),
                'last_page' => $pengajuan->lastPage(),
                'per_page' => $pengajuan->perPage(),
                'total' => $pengajuan->total(),
            ],
        ], 200);
    }

    /**
     * Menampilkan detail satu pengajuan UMKM.
     */
    public function show(Request $request, PengajuanUmkm $pengajuanUmkm)
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

        if (!$isAdmin && $pengajuanUmkm->user_id != $userData->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pengajuan ini.',
            ], 403);
        }

        $pengajuanUmkm->load('umkm');

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan UMKM berhasil diambil.',
            'data' => new PengajuanUmkmResource($pengajuanUmkm),
        ], 200);
    }

    /**
     * Menyimpan pengajuan UMKM baru.
     */
    public function store(StorePengajuanUmkmRequest $request): JsonResponse
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
        | Upload foto usaha
        |--------------------------------------------------------------------------
        */

        $fotoUsaha = [];

        if ($request->hasFile('foto_usaha')) {
            foreach ($request->file('foto_usaha') as $file) {
                $fotoUsaha[] = $file->store(
                    'pengajuan/umkm/usaha',
                    'public'
                );
            }
        }

        $data['foto_usaha'] = $fotoUsaha;

        /*
        |--------------------------------------------------------------------------
        | Upload KTP
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto_ktp')) {
            $data['foto_ktp'] = $request->file('foto_ktp')
                ->store(
                    'pengajuan/umkm/ktp',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload NIB
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('dokumen_nib')) {
            $data['dokumen_nib'] = $request->file('dokumen_nib')
                ->store(
                    'pengajuan/umkm/nib',
                    'public'
                );
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
        | Simpan ke database
        |--------------------------------------------------------------------------
        */

        $pengajuan = PengajuanUmkm::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan UMKM berhasil dikirim.',
            'data' => new PengajuanUmkmResource($pengajuan),
        ], 201);
    }

    /**
     * Mengubah status pengajuan UMKM.
     */
    public function updateStatus(
        UpdateStatusPengajuanUmkmRequest $request,
        PengajuanUmkm $pengajuanUmkm
    ) {
        $data = $request->validated();

        // Pengajuan yang sudah diproses tidak boleh diproses ulang
        if ($pengajuanUmkm->status !== 'pending') {
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

                // Ambil foto pertama sebagai gambar utama
                $gambar = null;

                if (
                    is_array($pengajuanUmkm->foto_usaha) &&
                    count($pengajuanUmkm->foto_usaha) > 0
                ) {
                    $gambar = $pengajuanUmkm->foto_usaha[0];
                }

                // Buat data UMKM
                $umkm = Umkm::create([
                    'nama' => $pengajuanUmkm->nama_umkm,
                    'deskripsi' => $pengajuanUmkm->deskripsi_umkm,
                    'lokasi' => $pengajuanUmkm->alamat_umkm,
                    'kategori' => $pengajuanUmkm->kategori,
                    'gambar' => $gambar,
                    'kontak' => $pengajuanUmkm->no_hp_pengaju,
                    'harga_mulai' => null,
                    'is_active' => true,
                ]);

                // Update status pengajuan
                $pengajuanUmkm->status = 'approved';
                $pengajuanUmkm->umkm_id = $umkm->id;
                $pengajuanUmkm->alasan_penolakan = null;
                $pengajuanUmkm->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan UMKM berhasil disetujui dan menjadi data UMKM.',
                    'data' => [
                        'pengajuan' => new PengajuanUmkmResource($pengajuanUmkm),
                        'umkm' => $umkm,
                    ],
                ], 200);
            }


            // =====================================================
            // REJECTED
            // =====================================================

            if ($data['status'] === 'rejected') {

                $pengajuanUmkm->status = 'rejected';
                $pengajuanUmkm->alasan_penolakan =
                    $data['alasan_penolakan'] ?? null;

                $pengajuanUmkm->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan UMKM berhasil ditolak.',
                    'data' => new PengajuanUmkmResource($pengajuanUmkm),
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
                'message' => 'Gagal memproses pengajuan UMKM.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menghapus pengajuan UMKM.
     */
    public function destroy(
        PengajuanUmkm $pengajuanUmkm
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | Hapus foto usaha
        |--------------------------------------------------------------------------
        */

        if (is_array($pengajuanUmkm->foto_usaha)) {
            foreach ($pengajuanUmkm->foto_usaha as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus KTP
        |--------------------------------------------------------------------------
        */

        if ($pengajuanUmkm->foto_ktp) {
            Storage::disk('public')->delete(
                $pengajuanUmkm->foto_ktp
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus dokumen NIB
        |--------------------------------------------------------------------------
        */

        if ($pengajuanUmkm->dokumen_nib) {
            Storage::disk('public')->delete(
                $pengajuanUmkm->dokumen_nib
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus data database
        |--------------------------------------------------------------------------
        */

        $pengajuanUmkm->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan UMKM berhasil dihapus.',
        ]);
    }
}