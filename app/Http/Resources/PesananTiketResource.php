<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PesananTiketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_pesanan' => $this->kode_pesanan,
            'wisata' => [
                'id' => $this->wisata->id,
                'nama' => $this->wisata->nama,
                'slug' => $this->wisata->slug,
            ],
            'nama_pemesan' => $this->nama_pemesan,
            'email_pemesan' => $this->email_pemesan,
            'no_hp_pemesan' => $this->no_hp_pemesan,
            'total_harga' => (float) $this->total_harga,
            'status' => $this->status,
            'snap_token' => $this->snap_token,
            'payment_type' => $this->payment_type,
            'paid_at' => $this->paid_at,
            'items' => $this->details->map(fn ($detail) => [
                'nama_tiket' => $detail->nama_tiket,
                'harga_satuan' => (float) $detail->harga_satuan,
                'jumlah' => $detail->jumlah,
                'subtotal' => (float) $detail->subtotal,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
