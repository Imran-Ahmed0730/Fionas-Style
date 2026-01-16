<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected $errorBag = 'updatePassword';

    public function rules(): array
    {
        return [
            'current_password'      => 'required|string|min:8|max:255',
            'password_confirmation' => 'required|string|min:8|max:255',
            'password'              => 'required|string|confirmed|min:8|max:255',
        ];
    }
}
