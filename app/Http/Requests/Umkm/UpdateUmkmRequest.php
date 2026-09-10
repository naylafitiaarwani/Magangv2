<?php

namespace App\Http\Requests\Umkm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('umkm')->id ?? $this->route('umkm');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('umkms', 'slug')->ignore($id),
            ],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_mulai' => ['nullable', 'numeric', 'min:0'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}