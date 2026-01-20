<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'code'                  => 'required|string|max:255|unique:coupons',
            'discount_type'         => 'required|in:1,2',
            'discount'              => 'required|numeric',
            'min_purchase_amount'   => 'required|numeric|min:1',
            'total_use_limit'       => 'required|integer|min:1',
            'use_limit_per_person'  => 'required|integer|min:1',
            'applicable_for'        => 'required|integer',
            'applicable_products'   => 'nullable|array',
            'start_date'            => 'required|datetime|before:end_date|after_or_equal:today',
            'end_date'              => 'required|datetime|after:start_date|after_or_equal:today',
            'status'                => 'required|in:0,1',
        ];
    }
}
