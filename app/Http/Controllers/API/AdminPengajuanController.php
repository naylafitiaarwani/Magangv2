<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengajuanUmkm;
use App\Models\PengajuanWisata;
use Illuminate\Http\Request;

class AdminPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'semua');
        $status = $request->get('status');
        $search = $request->get('search');

        $data = collect();

        /*
        |--------------------------------------------------------------------------
        | Pengajuan Wisata
        |--------------------------------------------------------------------------
        */
        if ($jenis === 'semua' || $jenis === 'wisata') {

            $queryWisata = PengajuanWisata::with('user')
                ->latest();

            if ($status) {
                $queryWisata->where('status', $status);
            }

            if ($search) {
                $queryWisata->where(function ($query) use ($search) {
                    $query->where('nama_wisata', 'like', "%{$search}%")
                        ->orWhere('nama_pengaju', 'like', "%{$search}%")
                        ->orWhere('email_pengaju', 'like', "%{$search}%");
                });
            }

            $wisata = $queryWisata->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis' => 'wisata',
                    'nama' => $item->nama_wisata,
                    'nama_pengaju' => $item->nama_pengaju,
                    'email_pengaju' => $item->email_pengaju,
                    'no_hp_pengaju' => $item->no_hp_pengaju,
                    'status' => $item->status,
                    'alasan_penolakan' => $item->alasan_penolakan,
                    'wisata_id' => $item->wisata_id,
                    'user_id' => $item->user_id,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            $data = $data->concat($wisata);
        }

        /*
        |--------------------------------------------------------------------------
        | Pengajuan UMKM
        |--------------------------------------------------------------------------
        */
        if ($jenis === 'semua' || $jenis === 'umkm') {

            $queryUmkm = PengajuanUmkm::with('user')
                ->latest();

            if ($status) {
                $queryUmkm->where('status', $status);
            }

            if ($search) {
                $queryUmkm->where(function ($query) use ($search) {
                    $query->where('nama_umkm', 'like', "%{$search}%")
                        ->orWhere('nama_pengaju', 'like', "%{$search}%")
                        ->orWhere('email_pengaju', 'like', "%{$search}%");
                });
            }

            $umkm = $queryUmkm->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis' => 'umkm',
                    'nama' => $item->nama_umkm,
                    'nama_pengaju' => $item->nama_pengaju,
                    'email_pengaju' => $item->email_pengaju,
                    'no_hp_pengaju' => $item->no_hp_pengaju,
                    'status' => $item->status,
                    'alasan_penolakan' => $item->alasan_penolakan,
                    'umkm_id' => $item->umkm_id,
                    'user_id' => $item->user_id,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            $data = $data->concat($umkm);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        $data = $data
            ->sortByDesc('created_at')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Pagination manual karena data berasal dari 2 tabel
        |--------------------------------------------------------------------------
        */
        $perPage = max(1, $request->integer('per_page', 10));
        $page = max(1, $request->integer('page', 1));

        $total = $data->count();

        $items = $data
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Data pengajuan berhasil diambil.',
            'data' => $items,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) ceil($total / $perPage),
            ],
            'filter' => [
                'jenis' => $jenis,
                'status' => $status,
                'search' => $search,
            ],
        ], 200);
    }
}