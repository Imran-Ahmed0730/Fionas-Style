<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStockRequest extends FormRequest
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
            'product_id'    => 'required|integer|exists:products,id',
            'buying_price'  => 'required|numeric',
            'sku'           => 'required|string|unique:product_stocks,sku',
            'supplier_id'   => 'nullable|integer|exists:suppliers,id',
            'quantity'      => 'required|array',
        ];
    }
}
