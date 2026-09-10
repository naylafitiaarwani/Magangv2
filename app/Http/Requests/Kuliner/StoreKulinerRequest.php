<?php

namespace App\Http\Requests\Kuliner;

use Illuminate\Foundation\Http\FormRequest;

class StoreKulinerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:kuliners,slug'],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_mulai' => ['nullable', 'numeric', 'min:0'],
            'lokasi' => ['required', 'string', 'max:255'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}