<?php

namespace App\Services\Frontend;

use App\Models\Admin\Order;
use App\Models\Admin\OrderItem;
use App\Models\Admin\Product;
use App\Models\Admin\ProductStock;
use App\Models\Admin\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Cart;

class OrderService
{
    /**
     * Place a new order
     */
    public function placeOrder(array $data, array $cartSummary)
    {
        return DB::transaction(function () use ($data, $cartSummary) {
            // 1. Check Stock Availability
            $this->checkStockAvailability($cartSummary['items']);

            // 2. Create Order
            $order = Order::create([
                'type'              => 1, // Online
                'invoice_no'        => 'FS-' . date('YmdHis') . rand(100, 999999),
                'customer_id'       => Auth::check() ? Auth::user()->customer_id ?? Auth::id() : null,
                'name'              => $data['name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'subtotal'          => $cartSummary['subtotal'],
                'tax'               => $cartSummary['tax'],
                'shipping_cost'     => $cartSummary['shipping_cost'],
                'discount'          => $cartSummary['discount'],
                'coupon_discount'   => $cartSummary['coupon_discount'],
                'grand_total'       => $cartSummary['grand_total'],
                'payment_status'    => '0', // Unpaid
                'country_id'        => $data['country_id'],
                'state_id'          => $data['state_id'],
                'city_id'           => $data['city'],
                'address'           => $data['address'],
                'note'              => $data['note'],
                'status'            => 0, // Pending
                'created_by'        => Auth::check() ? Auth::id() : 0,
            ]);

            // 3. Create Order Items and Deduct Stock
            foreach ($cartSummary['items'] as $item) {
                $productId = $item->attributes->productId;
                $variantName = $item->attributes->variant_name ?? null;
                $variantId = null;

                if ($variantName) {
                    $variant = ProductVariant::where('product_id', $productId)
                        ->where('name', $variantName)
                        ->first();
                    $variantId = $variant ? $variant->id : null;
                }

                $product = Product::find($productId);

                // Deduct Stock and get the stock_sku used
                $stockUsed = $this->deductStock($productId, $variantId, $item->quantity);

                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $productId,
                    'variant_id'        => $variantId,
                    'product_name'      => $product->name,
                    'sku'               => $item->id, // SKU is stored as item ID in cart
                    'stock_sku'         => $stockUsed['stock_sku'] ?? null,
                    'variant_name'      => $variantName,
                    'price'             => $item->price,
                    'quantity'          => $item->quantity,
                    'total'             => $item->price * $item->quantity,
                    'tax'               => $item->attributes->tax,
                ]);
            }

            // Clear Cart
            Cart::clear();

            return $order;
        });
    }

    /**
     * Check if enough stock is available for all items in cart
     */
    private function checkStockAvailability($items)
    {
        foreach ($items as $item) {
            $productId = $item->attributes->productId;
            $variantName = $item->attributes->variant_name ?? null;
            $requestedQty = $item->quantity;

            $totalStock = 0;
            if ($variantName) {
                $variant = ProductVariant::where('product_id', $productId)->where('name', $variantName)->first();
                if (!$variant) {
                    throw new \Exception("Variant {$variantName} not found for product {$item->name}");
                }
                $totalStock = ProductStock::where('product_id', $productId)
                    ->where('variant_sku', $variant->sku)
                    ->sum('qty');
            } else {
                $totalStock = ProductStock::where('product_id', $productId)
                    ->where(function ($q) {
                        $q->whereNull('variant_sku')->orWhere('variant_sku', '');
                    })
                    ->sum('qty');
            }

            if ($totalStock < $requestedQty) {
                throw new \Exception("Insufficient stock for {$item->name}" . ($variantName ? " ({$variantName})" : ""));
            }
        }
    }

    /**
     * Deduct stock starting from the first non-empty stock record (FIFO)
     * Returns array with stock_sku of the first stock used
     */
    private function deductStock($productId, $variantId, $quantityToDeduct)
    {
        $query = ProductStock::where('product_id', $productId);

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            $query->where('variant_sku', $variant->sku);
        } else {
            $query->where(function ($q) {
                $q->whereNull('variant_sku')->orWhere('variant_sku', '');
            });
        }

        $stocks = $query->where('qty', '>', 0)->orderBy('added_on', 'asc')->get();

        $remainingToDeduct = $quantityToDeduct;
        $firstStockUsed = null;

        foreach ($stocks as $stock) {
            if ($remainingToDeduct <= 0)
                break;

            // Track the first stock used
            if (!$firstStockUsed) {
                $firstStockUsed = [
                    'stock_sku' => $stock->sku,
                ];
            }

            if ($stock->qty >= $remainingToDeduct) {
                $stock->decrement('qty', $remainingToDeduct);
                $remainingToDeduct = 0;
            } else {
                $remainingToDeduct -= $stock->qty;
                $stock->update(['qty' => 0]);
            }
        }

        if ($remainingToDeduct > 0) {
            throw new \Exception("Error updating stock: Remaining quantity {$remainingToDeduct} for product ID {$productId}");
        }

        return $firstStockUsed ?? [];
    }
}
