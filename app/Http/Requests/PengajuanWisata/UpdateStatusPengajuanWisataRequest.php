<?php

namespace App\Http\Requests\PengajuanWisata;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusPengajuanWisataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                ]),
            ],

            'alasan_penolakan' => [
                'required_if:status,rejected',
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}