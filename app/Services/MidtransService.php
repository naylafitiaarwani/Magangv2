<?php

namespace App\Services;

use App\Models\PesananTiket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MidtransService
{
    protected string $serverKey;
    protected bool $isProduction;
    protected string $snapUrl;
    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);

        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $this->apiBaseUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';

        if (empty($this->serverKey)) {
            throw new RuntimeException(
                'MIDTRANS_SERVER_KEY belum diset di file .env.'
            );
        }
    }

    /**
     * Buat transaksi Snap dari sebuah PesananTiket dan kembalikan snap_token.
     * Pastikan relasi 'details' dan 'wisata' sudah di-load sebelum dipanggil,
     * misal: $pesanan->fresh(['wisata', 'details']).
     */
    public function createSnapTransaction(PesananTiket $pesanan): string
    {
        $grossAmount = (int) round($pesanan->total_harga);

        $itemDetails = $pesanan->details->map(function ($detail) {
            return [
                'id' => (string) $detail->tiket_wisata_id,
                'name' => mb_substr((string) $detail->nama_tiket, 0, 50),
                'price' => (int) round($detail->harga_satuan),
                'quantity' => (int) $detail->jumlah,
            ];
        })->values()->all();

        // Jaga-jaga: total item_details harus sama persis dengan gross_amount,
        // atau Midtrans akan menolak requestnya.
        $itemsTotal = array_sum(array_map(
            fn ($item) => $item['price'] * $item['quantity'],
            $itemDetails
        ));

        if ($itemsTotal !== $grossAmount) {
            // fallback: kalau ada selisih pembulatan, jangan kirim item_details
            $itemDetails = [];
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $pesanan->midtrans_order_id,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama_pemesan,
                'email' => $pesanan->email_pemesan,
                'phone' => $pesanan->no_hp_pemesan,
            ],
        ];

        if (!empty($itemDetails)) {
            $payload['item_details'] = $itemDetails;
        }

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($this->snapUrl, $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap gagal', [
                'order_id' => $pesanan->midtrans_order_id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException(
                'Gagal membuat transaksi Midtrans: ' .
                    ($response->json('error_messages.0') ?? $response->body())
            );
        }

        $token = $response->json('token');

        if (empty($token)) {
            throw new RuntimeException('Snap token tidak ditemukan pada response Midtrans.');
        }

        return $token;
    }

    /**
     * Cek status transaksi berdasarkan order_id (dipakai untuk webhook/notification
     * handler atau untuk mengecek status pesanan secara manual).
     */
    public function getStatus(string $orderId): array
    {
        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders(['Accept' => 'application/json'])
            ->get("{$this->apiBaseUrl}/{$orderId}/status");

        if ($response->failed()) {
            Log::error('Midtrans getStatus gagal', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Gagal mengambil status transaksi Midtrans.');
        }

        return $response->json();
    }

    /**
     * Verifikasi signature_key dari payload notification webhook Midtrans.
     * Panggil ini di controller notification handler sebelum memproses payload.
     */
    public function isValidSignature(array $payload): bool
    {
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $expected = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $this->serverKey
        );

        return hash_equals($expected, $signatureKey);
    }
}
