<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->id),
                'string',
                'max:255',
            ],
        ];

        if ($this->routeIs('admin.role.assign_permission.submit')) {
            $rules = [
                'permission' => 'required|array',
            ];
        }

        return $rules;
    }
}
