<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TiketWisataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'wisata_id' => $this->wisata_id,
            'nama_tiket' => $this->nama_tiket,
            'harga' => (float) $this->harga,
            'deskripsi' => $this->deskripsi,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
