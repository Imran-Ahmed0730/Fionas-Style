<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'salary' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('staff', 'email')->ignore($this->id),
                'max:255',
            ],
            'nid_no' => [
                'nullable',
                Rule::unique('staff', 'nid_no')->ignore($this->id),
                'max:255',
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed|max:255',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8|confirmed|max:255';
        }
        else{
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }
}
