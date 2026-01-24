<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'question' => 'required|unique:faqs,question,' . $this->id,
            'category_id' => 'required',
            'answer' => 'required',
            'status' => 'required',
        ];
    }
}
