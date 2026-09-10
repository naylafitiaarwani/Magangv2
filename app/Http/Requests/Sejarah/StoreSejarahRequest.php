<?php

namespace App\Http\Requests\Sejarah;

use Illuminate\Foundation\Http\FormRequest;

class StoreSejarahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:sejarahs,slug'],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}