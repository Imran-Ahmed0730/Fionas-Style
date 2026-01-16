<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'                  => [
                'required',
                Rule::unique('categories', 'name')->ignore($this->id),
            ],
            'description'           => 'nullable|string',
            'parent_id'             => 'nullable|integer',
            'priority'              => 'nullable|integer',
            'status'                => 'nullable|boolean',
            'meta_title'            => 'nullable|string|max:255',
            'meta_description'      => 'nullable|string|max:255',
            'meta_keywords'         => 'nullable|string|max:255',
            'meta_image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Description was required in controller
        $rules['description']       = 'required|string';

        if ($this->isMethod('post')) {
            $rules['icon']          = 'required|image|mimes:jpeg,png,jpg|max:2048';
            $rules['cover_photo']   = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['icon']          = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
            $rules['cover_photo']   = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }
}
