<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'nama' => $this->nama,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,

            'tanggal_mulai' => $this->tanggal_mulai?->format('Y-m-d'),
            'tanggal_selesai' => $this->tanggal_selesai?->format('Y-m-d'),

            'lokasi' => $this->lokasi,
            'penyelenggara' => $this->penyelenggara,

            'kategori' => $this->kategori,

            'gambar' => $this->gambar,

            'is_active' => $this->is_active,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
