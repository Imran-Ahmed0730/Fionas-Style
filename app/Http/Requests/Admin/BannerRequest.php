<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "title"         => "nullable|string|max:255",
            "subtitle"      => "nullable|string|max:255",
            "description"   => "nullable|string",
            "btn_text"      => "nullable|string|max:255",
            "link"          => "nullable|string|max:255",
            "priority"      => "nullable|integer",
            "position"      => "nullable|string",
            "status"        => "nullable|boolean",
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }
}
