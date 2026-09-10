<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukUmkmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'umkm_id' => $this->umkm_id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'gambar' => $this->gambar,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
