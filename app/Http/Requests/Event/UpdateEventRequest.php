<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eventId = $this->route('event');

        return [
            'nama' => ['required', 'string', 'max:255'],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('events', 'slug')->ignore($eventId),
            ],

            'deskripsi' => ['nullable', 'string'],

            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],

            'lokasi' => ['nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],

            'kategori' => ['nullable', 'string', 'max:100'],

            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
