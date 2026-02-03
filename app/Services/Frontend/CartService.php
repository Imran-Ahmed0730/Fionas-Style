<?php

namespace App\Services\Frontend;

use App\Models\Admin\Coupon;
use App\Models\Admin\Product;
use App\Models\Admin\State;
use App\Models\Admin\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Cart;

class CartService
{
    /**
     * Add or update product in cart
     */
    public function addToCart($request)
    {
        $product = Product::active()->where('slug', $request->slug)->firstOrFail();

        $price = $product->final_price;
        $sku = $product->sku;
        $name = $product->name;
        $attributes = [
            'productId' => $product->id,
            'image' => $product->thumbnail,
            'tax' => 0,
            'shipping_cost' => $product->shipping_cost ?? 0,
            'variant' => $request->variant,
        ];

        if ($request->variant) {
            $variant = $product->variants()->where('name', $request->variant)->first();
            if ($variant) {
                $price = $variant->final_price;
                $sku = $variant->sku;
                $attributes['variant_name'] = $variant->name;
            }
        }

        $tax_percentage = getSetting('tax_percentage', 0);
        $tax = ($price * $tax_percentage / 100) * $request->quantity;
        $attributes['tax'] = $tax;

        Cart::add([
            'id' => $sku,
            'name' => $name,
            'price' => $price,
            'quantity' => $request->quantity,
            'attributes' => $attributes
        ]);

        return $this->getCartData();
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($sku, $quantity)
    {
        $cartItem = Cart::get($sku);
        if (!$cartItem) {
            return ['success' => false, 'message' => 'Item not found in cart'];
        }

        $product = Product::find($cartItem->attributes->productId);
        $shipping_cost = $product->shipping_cost ?? 0;

        $tax_percentage = getSetting('tax_percentage', 0);
        $tax = ($cartItem->price * $tax_percentage / 100) * $quantity;

        Cart::update($sku, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
            'attributes' => array_merge($cartItem->attributes->toArray(), [
                'tax' => $tax,
                'shipping_cost' => $shipping_cost,
            ])
        ]);

        return $this->getCartData();
    }

    /**
     * Get consistent cart data
     */
    public function getCartData()
    {
        $items = Cart::getContent();
        $subtotal = 0;
        $tax = 0;

        foreach ($items as $item) {
            $subtotal += ($item->price * $item->quantity);
            $tax += $item->attributes->tax;
        }

        $sessionStateId = request('state_id') ?? Session::get('shipping_state_id');
        $shipping = $this->calculateShipping($sessionStateId);

        $couponCode = request('coupon_code') ?? Session::get('applied_coupon');
        $discountData = $this->getCouponDiscount($couponCode, $subtotal, $items);
        $couponDiscount = $discountData['discount'];

        return [
            'success' => true,
            'items' => $items,
            'total_quantity' => Cart::getTotalQuantity(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shipping,
            'discount' => $couponDiscount,
            'coupon_discount' => $couponDiscount,
            'coupon_code' => $couponCode,
            'coupon_error' => $discountData['error'] ?? null,
            'grand_total' => ceil(max(0, ($subtotal + $tax + $shipping) - $couponDiscount))
        ];
    }

    /**
     * Calculate shipping cost based on settings and location
     */
    public function calculateShipping($stateId = null)
    {
        $shippingMethod = getSetting('shipping_method', 'location_wise');
        $totalShipping = 0;
        $items = Cart::getContent();

        if (count($items) == 0)
            return 0;

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item->price * $item->quantity);
        }

        $freeDeliveryThreshold = (float) getSetting('free_delivery_threshold', 0);
        if ($freeDeliveryThreshold > 0 && $subtotal >= $freeDeliveryThreshold) {
            return 0;
        }

        if ($shippingMethod == 'product_wise') {
            foreach ($items as $item) {
                $product = Product::find($item->attributes->productId);
                if ($product && $product->shipping_cost > 0) {
                    $totalShipping += $product->shipping_cost * $item->quantity;
                }
            }
        } elseif ($shippingMethod == 'flat_rate') {
            $totalShipping = (float) getSetting('flat_rate_shipping_cost', 100);
        } else {
            // Location wise
            if (!$stateId) {
                return (float) getSetting('shipping_cost_inside_dhaka', 60);
            }

            $state = State::find($stateId);
            $insideDhaka = $state && str_contains(strtolower($state->name), 'dhaka');

            if ($insideDhaka) {
                $totalShipping = (float) getSetting('shipping_cost_inside_dhaka', 60);
            } else {
                $totalShipping = (float) getSetting('shipping_cost_outside_dhaka', 120);
            }
        }

        return $totalShipping;
    }

    /**
     * Calculate coupon discount
     */
    public function getCouponDiscount($code, $subtotal, $items = null)
    {
        if (empty($code))
            return ['discount' => 0];

        $coupon = Coupon::where('code', $code)
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return ['discount' => 0, 'error' => 'Invalid or expired coupon code'];
        }

        // Check Total Use Limit
        if ($coupon->total_use_limit > 0) {
            $usedCount = Order::where('coupon_id', $coupon->id)->count();
            if ($usedCount >= $coupon->total_use_limit) {
                return ['discount' => 0, 'error' => 'Coupon use limit reached'];
            }
        }

        // Check Per User Limit
        if ($coupon->use_limit_per_user > 0) {
            if (!Auth::check()) {
                return ['discount' => 0, 'error' => 'Please login to use this coupon'];
            }
            $userUsedCount = Order::where('coupon_id', $coupon->id)
                ->where('customer_id', Auth::id())
                ->count();
            if ($userUsedCount >= $coupon->use_limit_per_user) {
                return ['discount' => 0, 'error' => 'You have reached the use limit for this coupon'];
            }
        }

        // Check Applicable For specific customer
        if ($coupon->applicable_for > 0) {
            if (!Auth::check() || Auth::user()->customer_id != $coupon->applicable_for) {
                return ['discount' => 0, 'error' => 'This coupon is not applicable for your account'];
            }
        }

        // Check Applicable Products
        $applicableSubtotal = $subtotal;
        $applicableProducts = json_decode($coupon->applicable_products, true);

        if (!empty($applicableProducts) && is_array($applicableProducts)) {
            $applicableSubtotal = 0;
            $items = $items ?? Cart::getContent();
            foreach ($items as $item) {
                if (in_array($item->attributes->productId, $applicableProducts)) {
                    $applicableSubtotal += ($item->price * $item->quantity);
                }
            }
            if ($applicableSubtotal <= 0) {
                return ['discount' => 0, 'error' => 'This coupon is not applicable for the products in your cart'];
            }
        }

        // Check minimum purchase price
        if ($coupon->min_purchase_price > 0 && $applicableSubtotal < $coupon->min_purchase_price) {
            return ['discount' => 0, 'error' => 'Minimum purchase of ' . $coupon->min_purchase_price . ' required for this coupon'];
        }

        $discount = 0;
        if ($coupon->discount_type == 2) { // Percent
            $discount = ($applicableSubtotal * $coupon->discount) / 100;
        } else { // Flat
            $discount = $coupon->discount;
        }

        return ['discount' => $discount, 'coupon' => $coupon];
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($sku)
    {
        Cart::remove($sku);
        return $this->getCartData();
    }
}