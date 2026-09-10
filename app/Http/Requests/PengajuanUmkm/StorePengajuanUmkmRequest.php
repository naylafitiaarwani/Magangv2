<?php

namespace App\Http\Requests\PengajuanUmkm;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Data UMKM
            'nama_umkm' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat_umkm' => [
                'required',
                'string',
            ],

            'deskripsi_umkm' => [
                'required',
                'string',
            ],

            'kategori' => [
                'required',
                'string',
                'max:100',
            ],

            'produk_umkm' => [
                'nullable',
                'string',
                'max:1000',
            ],

            // Data pengaju
            'nama_pengaju' => [
                'required',
                'string',
                'max:255',
            ],

            'jabatan_pengaju' => [
                'nullable',
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

            // Foto usaha
            'foto_usaha' => [
                'required',
                'array',
                'min:1',
            ],

            'foto_usaha.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            // Dokumen
            'foto_ktp' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            'dokumen_nib' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],
        ];
    }
}