<?php

namespace App\Http\Requests\PengajuanWisata;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePengajuanWisataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Data wisata
            'nama_wisata' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat_wisata' => [
                'required',
                'string',
            ],

            'deskripsi_wisata' => [
                'required',
                'string',
            ],

            // Data pengaju
            'nama_pengaju' => [
                'required',
                'string',
                'max:255',
            ],

            'jabatan_pengaju' => [
                'required',
                'string',
                'max:255',
            ],

            'no_hp_pengaju' => [
                'required',
                'string',
                'max:30',
            ],

            'email_pengaju' => [
                'required',
                'email',
                'max:255',
            ],

            // Foto lokasi, boleh lebih dari satu
            'foto_lokasi_wisata' => [
                'required',
                'array',
                'min:1',
            ],

            'foto_lokasi_wisata.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            // Status entitas
            'status_entitas' => [
                'required',
                Rule::in([
                    'perorangan',
                    'organisasi',
                ]),
            ],

            // Dokumen perorangan
            'foto_ktp' => [
                'required_if:status_entitas,perorangan',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            'foto_selfie' => [
                'required_if:status_entitas,perorangan',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            // Data organisasi
            'nama_organisasi' => [
                'required_if:status_entitas,organisasi',
                'nullable',
                'string',
                'max:255',
            ],

            'dokumen_nib' => [
                'required_if:status_entitas,organisasi',
                'array',
                'min:1',
            ],

            'dokumen_nib.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            // Tiket online
            'active_online_tiket' => [
                'required_if:status_entitas,organisasi',
                'nullable',
                Rule::in([
                    'Aktif',
                    'Tidak Aktif',
                ]),
            ],
        ];
    }
}