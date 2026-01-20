<?php

namespace App\Services;

use App\Models\Admin\Product;
use App\Models\Admin\ProductStock;
use App\Models\Admin\ProductVariant;
use Illuminate\Support\Str;

class ProductStockService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateSku(): string
    {
        $sku_syntax = getSetting('variant_product_sku_syntax');
        do {
            // Generate a SKU with format 'sk0001' to 'sk9999'
            $sku = Str::replace('[[random_number]]', '', $sku_syntax) . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Check if SKU is unique
            $isUnique = !ProductStock::where('sku', $sku)->exists();
        } while (!$isUnique); // Retry if SKU is not unique

        return $sku;
    }

    public function create(Product $product, array $data): bool
    {
        $total_stock = 0;

        if (isset($data['quantity']['default'])) {
            $qty = $data['quantity']['default'];
            ProductStock::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'supplier_id' => $data['supplier_id'] ?? null,
                'sku' => $data['sku'],
                'variant_name' => null,
                'variant_sku' => null,
                'qty' => $qty,
                'buying_price' => $data['buying_price'],
                'added_by' => auth()->id(),
                'added_on' => now(),
            ]);
            $total_stock = $qty;
        } else {
            foreach ($data['quantity'] as $variant_sku => $qty) {
                $product_variant = ProductVariant::where('product_id', $product->id)->where('sku', $variant_sku)->first();
                ProductStock::create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'supplier_id' => $data['supplier_id'] ?? null,
                    'sku' => $data['sku'],
                    'variant_sku' => $variant_sku,
                    'variant_name' => $product_variant->name ?? '',
                    'qty' => $qty,
                    'buying_price' => $data['buying_price'],
                    'added_by' => auth()->id(),
                    'added_on' => now(),
                ]);

                if ($product_variant) {
                    $product_variant->update([
                        'stock_qty' => $product_variant->stock_qty + $qty,
                    ]);
                }
                $total_stock += $qty;
            }
        }

        return $product->update([
            'stock_qty' => $product->stock_qty + $total_stock,
        ]);
    }
}
