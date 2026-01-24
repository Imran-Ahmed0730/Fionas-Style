<?php

namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
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
        // For store (create) operation
        if ($this->isMethod('POST') && !$this->id) {
            $thumbnailRule = 'required|image|mimes:jpeg,png,jpg,webp|max:2048';
        } else {
            $thumbnailRule = 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';
        }

        return [
            // Primary Info validation
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'category_id' => 'required',
            'thumbnail' => $thumbnailRule,
            'status' => 'required|in:0,1',

            // Product Info validation
            'product_id' => 'nullable|array',
            'product_id.*' => 'nullable|exists:products,id',
            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|array',
            'discount_type.*' => 'nullable|in:1,2', // 1=Flat, 2=Percent
            'final_price' => 'nullable|array',
            'final_price.*' => 'nullable|numeric|min:0',

            // SEO validation
            'meta_title'        => 'nullable|string|max:60',
            'meta_keywords'     => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:160',
            'meta_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'subtitle.max' => 'The subtitle may not be greater than 255 characters.',
            'description.required' => 'The description field is required.',
            'duration.required' => 'The duration field is required.',
            'category_id.required' => 'The category field is required.',
            'thumbnail.required' => 'The thumbnail field is required.',
            'status.required' => 'The status field is required.',
            'product_id.*.exists' => 'The selected product does not exist.',
            'discount.*.numeric' => 'The discount must be a number.',
            'discount.*.min' => 'The discount must be greater than or equal to 0.',
            'discount_type.*.in' => 'The discount type must be 1 or 2.',
            'final_price.*.numeric' => 'The final price must be a number.',
            'final_price.*.min' => 'The final price must be greater than or equal to 0.',
            'meta_title.max' => 'The meta title may not be greater than 60 characters.',
            'meta_keywords.max' => 'The meta keywords may not be greater than 255 characters.',
            'meta_description.max' => 'The meta description may not be greater than 160 characters.',
            'meta_image.required' => 'The meta image field is required.',
        ];
    }
}
