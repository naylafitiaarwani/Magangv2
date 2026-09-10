<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanWisataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'wisata_id' => $this->wisata_id,

            'nama_wisata' => $this->nama_wisata,
            'alamat_wisata' => $this->alamat_wisata,
            'deskripsi_wisata' => $this->deskripsi_wisata,

            'nama_pengaju' => $this->nama_pengaju,
            'jabatan_pengaju' => $this->jabatan_pengaju,
            'no_hp_pengaju' => $this->no_hp_pengaju,
            'email_pengaju' => $this->email_pengaju,

            'foto_lokasi_wisata' => $this->foto_lokasi_wisata,

            'status_entitas' => $this->status_entitas,
            'foto_ktp' => $this->foto_ktp,
            'foto_selfie' => $this->foto_selfie,
            'nama_organisasi' => $this->nama_organisasi,
            'dokumen_nib' => $this->dokumen_nib,
            'active_online_tiket' => $this->active_online_tiket,

            'status' => $this->status,
            'alasan_penolakan' => $this->alasan_penolakan,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'wisata' => $this->whenLoaded('wisata', function () {
                return $this->wisata ? [
                    'id' => $this->wisata->id,
                    'nama' => $this->wisata->nama,
                    'slug' => $this->wisata->slug,
                    'is_active' => $this->wisata->is_active,
                ] : null;
            }),
        ];
    }
}