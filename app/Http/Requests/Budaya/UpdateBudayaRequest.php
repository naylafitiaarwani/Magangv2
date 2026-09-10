<?php

namespace App\Http\Requests\Budaya;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudayaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('budaya')->id ?? $this->route('budaya');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('budayas', 'slug')->ignore($id),
            ],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:255'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}