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
        $rules = [
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'code'                  => 'required|string|max:255|unique:coupons,code,' . $this->id,
            'discount_type'         => 'required|in:1,2',
            'discount'              => 'required|numeric',
            'min_purchase_price'    => 'required|numeric|min:1',
            'total_use_limit'       => 'required|integer|min:1',
            'use_limit_per_user'    => 'required|integer|min:1',
            'applicable_for'        => 'required|integer',
            'applicable_products'   => 'nullable|array',
            'applicable_products.*' => 'required|integer|exists:products,id',
            'start_date'            => 'required|date|before:end_date',
            'end_date'              => 'required|date|after:start_date|after_or_equal:today',
            'status'                => 'required|in:0,1',
        ];
        if(!$this->id){
            $rules['start_date'] = 'required|date|after_or_equal:today';
        }
        return $rules;
    }
}
