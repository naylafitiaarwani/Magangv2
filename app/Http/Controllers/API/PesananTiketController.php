<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PesananTiket\StorePesananTiketRequest;
use App\Http\Resources\PesananTiketResource;
use App\Models\PesananTiket;
use App\Models\TiketWisata;
use App\Models\Wisata;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananTiketController extends Controller
{
    public function __construct(private MidtransService $midtransService)
    {
    }

    /**
     * Riwayat pesanan tiket milik user yang login.
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

        $pesanan = PesananTiket::with(['wisata', 'details'])
            ->where('user_id', $userData->id)
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pesanan tiket berhasil diambil',
            'data' => PesananTiketResource::collection($pesanan),
            'pagination' => [
                'current_page' => $pesanan->currentPage(),
                'last_page' => $pesanan->lastPage(),
                'per_page' => $pesanan->perPage(),
                'total' => $pesanan->total(),
            ],
        ]);
    }

    /**
     * Detail satu pesanan berdasarkan kode pesanan.
     */
    public function show(Request $request, string $kodePesanan)
    {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $pesanan = PesananTiket::with(['wisata', 'details'])
            ->where('kode_pesanan', $kodePesanan)
            ->where('user_id', $userData->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil diambil',
            'data' => new PesananTiketResource($pesanan),
        ]);
    }

    /**
     * Checkout tiket: buat pesanan (status pending) lalu minta snap_token
     * ke Midtrans supaya bisa langsung dibayar dari frontend.
     */
    public function store(StorePesananTiketRequest $request, string $slug)
    {
        $userData = $request->attributes->get('userData');

        if (!$userData || empty($userData->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan dari access token.',
            ], 401);
        }

        $wisata = Wisata::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (!$wisata->is_tiket_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata ini belum melayani pembelian tiket online.',
            ], 400);
        }

        $data = $request->validated();

        // Ambil data tiket master (harus milik wisata yang sama & aktif)
        $tiketIds = collect($data['items'])->pluck('tiket_wisata_id');

        $tiketMaster = TiketWisata::where('wisata_id', $wisata->id)
            ->where('is_active', true)
            ->whereIn('id', $tiketIds)
            ->get()
            ->keyBy('id');

        if ($tiketMaster->count() !== $tiketIds->unique()->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Salah satu jenis tiket tidak ditemukan atau sudah tidak aktif.',
            ], 422);
        }

        try {
            $pesanan = DB::transaction(function () use ($data, $wisata, $userData, $tiketMaster) {
                $totalHarga = 0;
                $itemsData = [];

                foreach ($data['items'] as $item) {
                    $tiket = $tiketMaster[$item['tiket_wisata_id']];
                    $subtotal = (float) $tiket->harga * $item['jumlah'];
                    $totalHarga += $subtotal;

                    $itemsData[] = [
                        'tiket_wisata_id' => $tiket->id,
                        'nama_tiket' => $tiket->nama_tiket,
                        'harga_satuan' => $tiket->harga,
                        'jumlah' => $item['jumlah'],
                        'subtotal' => $subtotal,
                    ];
                }

                $kodePesanan = 'TKT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

                $pesanan = PesananTiket::create([
                    'kode_pesanan' => $kodePesanan,
                    'user_id' => $userData->id,
                    'wisata_id' => $wisata->id,
                    'nama_pemesan' => $data['nama_pemesan'],
                    'email_pemesan' => $data['email_pemesan'],
                    'no_hp_pemesan' => $data['no_hp_pemesan'],
                    'total_harga' => $totalHarga,
                    'status' => 'pending',
                    // order_id Midtrans harus unik; pakai kode pesanan + suffix waktu
                    'midtrans_order_id' => $kodePesanan . '-' . now()->timestamp,
                ]);

                $pesanan->details()->createMany($itemsData);

                return $pesanan;
            });

            $snapToken = $this->midtransService->createSnapTransaction(
                $pesanan->fresh('details')
            );

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat, silakan lanjutkan pembayaran.',
                'data' => [
                    'pesanan' => new PesananTiketResource($pesanan->fresh(['wisata', 'details'])),
                    'snap_token' => $snapToken,
                ],
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan tiket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
