<?php

namespace App\Http\Requests\TiketWisata;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTiketWisataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_tiket' => ['sometimes', 'required', 'string', 'max:255'],
            'harga' => ['sometimes', 'required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
