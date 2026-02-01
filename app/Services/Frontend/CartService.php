<?php

namespace App\Services\Frontend;

use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Cart;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Add item to cart
     */
    public function addToCart($request)
    {
        $product = Product::where('slug', $request->slug)->firstOrFail();
        $regular_price = $product->regular_price;
        $final_price = $product->final_price;

        // Validate Variant
        $variant = null;
        if (isset($request->variant) && !empty($request->variant)) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('name', $request->variant)->first();

            if ($variant) {
                $regular_price = $variant->regular_price;
                $final_price = $variant->final_price;

                if ($variant->stock_qty < ($request->quantity ?? $product->min_buying_qty)) {
                    return ['error' => 'Product variant out of stock'];
                }
            } else {
                return ['error' => 'Variant not found'];
            }
        } elseif ($product->stock_qty < ($request->quantity ?? $product->min_buying_qty)) {
            // Fallback to product level stock check if implementation uses it
            return ['error' => 'Product out of stock'];
        }

        // Tax Calculation
        // Assuming getSetting() helper exists
        $tax = 0;
        if (function_exists('getSetting')) {
            $tax = $final_price * ($product->tax ?? getSetting('product_tax'))  / 100 * ($request->quantity ?? 1);
            if (getSetting('tax_calculation') != 1 && $product->tax_inclusion == 2) {
                $tax = $final_price * $product->tax / 100 * ($request->quantity ?? 1);
            }
        }

        $min_payable = 0;
        if ($product->cod_available == 0) {
            $min_payable = $final_price * ($request->quantity ?? 1);
        }

        $shipping_cost = 0;
        if (function_exists('getProductShippingCost')) {
            $shipping_cost = getProductShippingCost($product, $request->quantity ?? 1);
        }

        $cartItem = Cart::get($variant ? $variant->sku : $product->sku);

        if ($cartItem) {
            $quantity = $cartItem->quantity + ($request->quantity ?? 1);

            // Re-validate stock for updated quantity
            if ($variant) {
                if ($variant->stock_qty < $quantity) {
                    return ['error' => 'Product variant out of stock for requested quantity'];
                }
            } elseif ($product->stock_qty < $quantity) {
                return ['error' => 'Product out of stock for requested quantity'];
            }

            Cart::update($variant ? $variant->sku : $product->sku, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity
                ],
                'attributes' => [
                    'image' => $cartItem->attributes['image'],
                    'productId' => $cartItem->attributes['productId'],
                    'variant' => $cartItem->attributes['variant'],
                    'variant_attr' => $cartItem->attributes['variant_attr'], // This is important for front-end list
                    'tax' => $tax, // Note: tax logic might need to recalculate for total qty, but reference updates partial
                    'min_payable' => $min_payable ?? 0,
                    'shipping_cost' => $shipping_cost,
                    'free_shipping' => $cartItem->attributes['free_shipping'],
                    'check_for_order_placement' => $cartItem->attributes['check_for_order_placement'],
                ]
            ]);
        } else {
            Cart::add(
                $variant ? $variant->sku : $product->sku,
                $product->name,
                $final_price,
                $request->quantity ?? $product->min_buying_qty ?? 1,
                [
                    'image' => $variant && $variant->image ? $variant->image : $product->thumbnail,
                    'productId' => $product->id,
                    'slug' => $product->slug,
                    'variant' => $request->variant, // Just the name e.g. "Color-Size"
                    'variant_attr' => $variant ? $variant->attr_name : null, // Display friendlier parts if needed
                    'tax' => $tax,
                    'min_payable' => $min_payable ?? 0,
                    'shipping_cost' => $shipping_cost,
                    'free_shipping' => $product->shipping_cost > 0 ? 0 : 1,
                    'check_for_order_placement' => 1,
                ]
            );
        }

        return Cart::getContent();
    }

    /**
     * Update cart item
     */
    public function updateCart($request)
    {
        $quantity = $request->quantity ?? 1;
        $sku = $request->sku;

        $cartItem = Cart::get($sku);

        if (!$cartItem) {
            return ['error' => 'Cart item not found'];
        }

        $product = Product::find($cartItem->attributes['productId']);

        if (!$product) {
            return ['error' => 'Product not found'];
        }

        // Validate Stock
        if ($cartItem->attributes['variant']) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('name', $cartItem->attributes['variant'])->first();
            if ($variant && $variant->stock_qty < $quantity) {
                return ['error' => 'Product variant out of stock'];
            }
        } elseif ($product->stock_qty < $quantity) {
            return ['error' => 'Product out of stock'];
        }

        $shipping_cost = $product->shipping_cost ?? 0;

        $tax = 0;
        if (function_exists('getSetting')) {
            $tax = $cartItem->price * ($product->tax ?? 0) / 100 * $quantity;
        }

        Cart::update($sku, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
            'attributes' => array_merge($cartItem->attributes->toArray(), [
                'tax' => $tax,
                'shipping_cost' => $shipping_cost,
                // keep other attributes
            ])
        ]);

        return ['success' => true, 'tax' => $tax, 'shipping_cost' => $shipping_cost];
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($sku)
    {
        Cart::remove($sku);
        return Cart::get($sku) == null;
    }

    /**
     * Update Checked Status (for checkout selection)
     */
    public function updateCheckStatus($sku, $status)
    {
        $cartItem = Cart::get($sku);
        if ($cartItem) {
            Cart::update($sku, [
                'attributes' => array_merge($cartItem->attributes->toArray(), [
                    'check_for_order_placement' => $status
                ])
            ]);
            return true;
        }
        return false;
    }
}
