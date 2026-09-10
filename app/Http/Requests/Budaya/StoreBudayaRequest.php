<?php

namespace App\Http\Requests\Budaya;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudayaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:budayas,slug'],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:255'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}