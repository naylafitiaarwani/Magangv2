<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengajuanUmkm;
use App\Models\PengajuanWisata;
use App\Models\Umkm;
use App\Models\Wisata;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Statistik pengajuan wisata
        $pengajuanWisataPending = PengajuanWisata::where('status', 'pending')->count();
        $pengajuanWisataApproved = PengajuanWisata::where('status', 'approved')->count();
        $pengajuanWisataRejected = PengajuanWisata::where('status', 'rejected')->count();

        // Statistik pengajuan UMKM
        $pengajuanUmkmPending = PengajuanUmkm::where('status', 'pending')->count();
        $pengajuanUmkmApproved = PengajuanUmkm::where('status', 'approved')->count();
        $pengajuanUmkmRejected = PengajuanUmkm::where('status', 'rejected')->count();

        // Gabungan statistik pengajuan
        $totalPending =
            $pengajuanWisataPending +
            $pengajuanUmkmPending;

        $totalApproved =
            $pengajuanWisataApproved +
            $pengajuanUmkmApproved;

        $totalRejected =
            $pengajuanWisataRejected +
            $pengajuanUmkmRejected;

        $totalPengajuan =
            $totalPending +
            $totalApproved +
            $totalRejected;

        // Statistik data wisata
        $totalWisata = Wisata::count();
        $wisataAktif = Wisata::where('is_active', true)->count();

        // Statistik data UMKM
        $totalUmkm = Umkm::count();
        $umkmAktif = Umkm::where('is_active', true)->count();

        // Pengajuan wisata terbaru
        $pengajuanWisataTerbaru = PengajuanWisata::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis' => 'wisata',
                    'nama' => $item->nama_wisata,
                    'nama_pengaju' => $item->nama_pengaju,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            });

        // Pengajuan UMKM terbaru
        $pengajuanUmkmTerbaru = PengajuanUmkm::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis' => 'umkm',
                    'nama' => $item->nama_umkm,
                    'nama_pengaju' => $item->nama_pengaju,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            });

        // Gabungkan dan urutkan berdasarkan tanggal terbaru
        $pengajuanTerbaru = $pengajuanWisataTerbaru
            ->concat($pengajuanUmkmTerbaru)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil.',
            'data' => [
                'pengajuan' => [
                    'total' => $totalPengajuan,
                    'pending' => $totalPending,
                    'approved' => $totalApproved,
                    'rejected' => $totalRejected,
                ],

                'pengajuan_wisata' => [
                    'pending' => $pengajuanWisataPending,
                    'approved' => $pengajuanWisataApproved,
                    'rejected' => $pengajuanWisataRejected,
                ],

                'pengajuan_umkm' => [
                    'pending' => $pengajuanUmkmPending,
                    'approved' => $pengajuanUmkmApproved,
                    'rejected' => $pengajuanUmkmRejected,
                ],

                'wisata' => [
                    'total' => $totalWisata,
                    'aktif' => $wisataAktif,
                ],

                'umkm' => [
                    'total' => $totalUmkm,
                    'aktif' => $umkmAktif,
                ],

                'pengajuan_terbaru' => $pengajuanTerbaru,
            ],
        ], 200);
    }
}