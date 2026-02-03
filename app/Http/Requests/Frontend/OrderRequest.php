<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'country_id' => 'required|integer|exists:countries,id',
            'state_id' => 'required|integer|exists:states,id',
            'city' => 'required|integer|exists:cities,id',
            'address' => 'required|string',
            'note' => 'nullable|string',
            'payment_method' => 'required',
        ];
    }
}
