<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value'         => 'required|string|max:255',
            'attribute_id'  => 'required|exists:attributes,id',
        ];
    }
}
