<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Order;

class HasPurchasedProduct implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();

        if (!$user) {
            $fail('You must be logged in to leave a review.');
            return;
        }

        if (!$user->customer) {
            $fail('Only registered customers can leave reviews.');
            return;
        }

        $hasPurchased = Order::where('customer_id', $user->customer->id)
            ->whereHas('items', function ($q) use ($value) {
                $q->where('product_id', $value);
            })
            ->where('payment_status', 1)
            ->exists();

        if (!$hasPurchased) {
            $fail('You must purchase this product to leave a review.');
        }
    }
}
