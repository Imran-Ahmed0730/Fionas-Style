<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "title"       => "nullable|string|max:255",
            "subtitle"    => "nullable|string|max:255",
            'description' => 'nullable|string',
            "btn_text"    => "nullable|string|max:255",
            "link"        => "nullable|string|max:255",
            'image'       => 'image|mimes:jpeg,png,jpg|max:2048',
            'priority'    => 'required|integer',
            'position'    => 'required',
            'status'      => 'required|in:0,1',
        ];

        if (!$this->id) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'subtitle.required' => 'Subtitle is required',
            'description.required' => 'Description is required',
            'btn_text.required' => 'Button text is required',
            'link.required' => 'Link is required',
            'image.required' => 'Image is required',
            'priority.required' => 'Priority is required',
            'position.required' => 'Position is required',
            'status.required' => 'Status is required',
        ];
    }
}
