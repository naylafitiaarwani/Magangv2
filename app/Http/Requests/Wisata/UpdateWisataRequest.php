<?php

namespace App\Http\Requests\Wisata;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWisataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('wisata')->id ?? $this->route('wisata');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('wisatas', 'slug')->ignore($id),
            ],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
            'harga_mulai' => ['nullable', 'numeric', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'jumlah_ulasan' => ['nullable', 'integer', 'min:0'],
            'kategori' => ['required', 'string', 'max:100'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}