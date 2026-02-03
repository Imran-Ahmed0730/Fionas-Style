<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountHeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:account_heads,id',
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('account_heads', 'title')->ignore($this->id),
            ],
            'type' => 'required|in:1,2',
        ];
    }
}
