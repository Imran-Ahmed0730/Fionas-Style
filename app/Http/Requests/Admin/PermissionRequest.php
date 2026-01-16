<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('permissions', 'name')->ignore($this->id),
                'string',
                'max:255',
            ],
            'suffix' => 'nullable|array',
            'status' => 'nullable|boolean',
        ];
    }
}
