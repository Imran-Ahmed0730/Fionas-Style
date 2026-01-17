<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:products,name,' . $this->id,
            'short_description' => 'required|string',
            'detailed_description' => 'required|string',
            'additional_information' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'unit_id' => 'nullable|integer|exists:units,id',
            'sku' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'regular_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:1,2',
            'tax_inclusion' => 'nullable|in:1,2',
            'tax' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'point' => 'nullable|integer|min:0',
            'thumbnail' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,jpg|max:2048' : 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|integer|in:0,1',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_time' => 'nullable|string|max:255',
            'shipping_return_policy' => 'nullable|string',
            'cod_available' => 'nullable|integer|in:0,1',
            'include_to_todays_deal' => 'nullable|integer|in:0,1',
            'is_featured' => 'nullable|integer|in:0,1',
            'is_replaceable' => 'nullable|integer|in:0,1',
            'is_trending' => 'nullable|integer|in:0,1',
            'color_id' => 'nullable|array',
            'attribute_id' => 'nullable|array',
            'attribute_value_id' => 'nullable|array',
            'variant' => 'nullable|array',
            'existing_variant' => 'nullable|array',
        ];

        return $rules;
    }
}
