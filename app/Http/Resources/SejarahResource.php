<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SejarahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $images = $this->images
            ->pluck('gambar')
            ->values()
            ->toArray();

        // Jika belum ada gambar di tabel sejarah_images,
        // gunakan gambar lama dari kolom sejarahs.gambar
        if (empty($images) && $this->gambar) {
            $images = [$this->gambar];
        }

        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'lokasi' => $this->lokasi,
            'kategori' => $this->kategori,

            'gambar' => $images,

            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}