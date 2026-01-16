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
            "title"=> "nullable|string|max:255",
            "subtitle"=> "nullable|string|max:255",
            "button_text"=> "nullable|string|max:255",
            "button_url"=> "nullable|string|max:255",
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }
}
