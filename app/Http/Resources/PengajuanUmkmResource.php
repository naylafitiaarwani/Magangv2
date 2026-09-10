<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanUmkmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'umkm_id' => $this->umkm_id,

            'nama_umkm' => $this->nama_umkm,
            'alamat_umkm' => $this->alamat_umkm,
            'deskripsi_umkm' => $this->deskripsi_umkm,
            'kategori' => $this->kategori,
            'produk_umkm' => $this->produk_umkm,

            'nama_pengaju' => $this->nama_pengaju,
            'jabatan_pengaju' => $this->jabatan_pengaju,
            'no_hp_pengaju' => $this->no_hp_pengaju,
            'email_pengaju' => $this->email_pengaju,

            'foto_usaha' => $this->foto_usaha,
            'foto_ktp' => $this->foto_ktp,
            'dokumen_nib' => $this->dokumen_nib,

            'status' => $this->status,
            'alasan_penolakan' => $this->alasan_penolakan,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'umkm' => $this->whenLoaded('umkm', function () {
                return $this->umkm ? [
                    'id' => $this->umkm->id,
                    'nama' => $this->umkm->nama,
                    'slug' => $this->umkm->slug,
                    'is_active' => $this->umkm->is_active,
                ] : null;
            }),
        ];
    }
}