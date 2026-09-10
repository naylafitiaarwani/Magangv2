<?php

namespace App\Http\Requests\Kuliner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKulinerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('kuliner')->id ?? $this->route('kuliner');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('kuliners', 'slug')->ignore($id),
            ],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_mulai' => ['nullable', 'numeric', 'min:0'],
            'lokasi' => ['required', 'string', 'max:255'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}