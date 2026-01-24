<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|unique:faq_categories,name,' . $this->id,
            'status' => 'required',
            'question' => 'nullable|array',
            'question.*' => 'required|string',
            'answer' => 'nullable|array',
            'answer.*' => 'required|string',
        ];
    }
}
