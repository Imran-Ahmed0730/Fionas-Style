<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderPaymentRequest extends FormRequest
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
            'payment_method' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'account_number' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255|unique:order_payments,transaction_id',
            'comment' => 'nullable|string',
        ];
    }
}
