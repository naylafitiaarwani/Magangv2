<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KulinerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'kategori' => $this->kategori,
            'harga_mulai' => $this->harga_mulai,
            'lokasi' => $this->lokasi,
            'gambar' => $this->gambar,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}