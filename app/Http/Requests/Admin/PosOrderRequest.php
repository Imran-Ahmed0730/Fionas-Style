<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PosOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_id' => 'nullable',
            'subtotal' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'shipping_cost' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'is_hold' => 'nullable',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_email' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string',
        ];

        if ($this->has('is_hold') && $this->is_hold) {
            // Rules for holding an order
            $rules += [
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
            ];
        } else {
            // Rules for placing a completed order
            $rules += [
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'payments' => 'required|array|min:1',
                'payments.*.method_id' => 'required|exists:payment_methods,id',
                'payments.*.amount' => 'required|numeric|min:0',
                'order_type' => 'required|in:pickup,delivery',
            ];

            if ($this->order_type === 'delivery') {
                $rules += [
                    'shipping_info.country_id' => 'required|exists:countries,id',
                    'shipping_info.state_id' => 'required|exists:states,id',
                    'shipping_info.city_id' => 'required|exists:cities,id',
                    'shipping_info.address' => 'required|string',
                ];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Cart is empty!',
            'payments.required' => 'Please add at least one payment method.',
            'customer_name.required' => 'Customer name is required.',
            'customer_phone.required' => 'Customer phone is required.',
            'shipping_info.state_id.required' => 'Shipping state is required for delivery.',
            'shipping_info.city_id.required' => 'Shipping city is required for delivery.',
            'shipping_info.address.required' => 'Shipping address is required for delivery.',
        ];
    }
}
