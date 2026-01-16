<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::id()),
                'max:255',
            ],
            'image'     => 'nullable|mimes:jpeg,png,jpg|max:2048',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
        ];
    }
}
