<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WisataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'lokasi' => $this->lokasi,
            'jam_operasional' => $this->jam_operasional,
            'harga_mulai' => $this->harga_mulai,
            'rating' => $this->rating,
            'jumlah_ulasan' => $this->jumlah_ulasan,
            'kategori' => $this->kategori,
            'gambar' => $this->gambar,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}