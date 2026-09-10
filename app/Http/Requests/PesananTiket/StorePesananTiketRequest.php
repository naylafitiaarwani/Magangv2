<?php

namespace App\Http\Requests\PesananTiket;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pemesan' => ['required', 'string', 'max:255'],
            'email_pemesan' => ['required', 'email', 'max:255'],
            'no_hp_pemesan' => ['required', 'string', 'max:30'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.tiket_wisata_id' => ['required', 'integer', 'exists:tiket_wisatas,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ];
    }
}
