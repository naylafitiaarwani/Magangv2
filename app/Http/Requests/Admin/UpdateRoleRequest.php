<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'privileges' => [
                'nullable',
                'array',
            ],

            'privileges.*.menu_id' => [
                'required',
                'integer',
                'exists:menus,id',
            ],

            'privileges.*.view' => [
                'nullable',
                'integer',
                Rule::in([0, 1]),
            ],

            'privileges.*.add' => [
                'nullable',
                'integer',
                Rule::in([0, 1]),
            ],

            'privileges.*.edit' => [
                'nullable',
                'integer',
                Rule::in([0, 1]),
            ],

            'privileges.*.delete' => [
                'nullable',
                'integer',
                Rule::in([0, 1]),
            ],

            'privileges.*.other' => [
                'nullable',
                'integer',
                Rule::in([0, 1]),
            ],
        ];
    }
}