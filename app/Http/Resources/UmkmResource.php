<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProdukUmkmResource;
class UmkmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'nama_pelaku' => $this->nama_pelaku,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'lokasi' => $this->lokasi,
            'kategori' => $this->kategori,
            'harga_mulai' => $this->harga_mulai,
            'gambar' => $this->gambar,
            'kontak' => $this->kontak,
            'is_active' => (bool) $this->is_active,
              'produk' => ProdukUmkmResource::collection(
                $this->whenLoaded('produk')
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}