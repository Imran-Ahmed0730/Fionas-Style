<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255|unique:blogs,title,' . $this->id,
            'description' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:0,1',
        ];

        if (!$this->id) {
            $rules['thumbnail'] = 'required|image';
        } else {
            $rules['thumbnail'] = 'nullable|image';
        }

        return $rules;
    }
}
