<?php

namespace App\Services\Frontend;

use App\Models\Admin\Product;
use Illuminate\Support\Facades\Session;

class CompareService
{
    const COMPARE_SESSION_KEY = 'product_comparison';
    const MAX_COMPARE_ITEMS = 4;

    /**
     * Get all compared products
     */
    public function getComparedProducts()
    {
        $productIds = $this->getComparedProductIds();

        if (empty($productIds)) {
            return [];
        }

        return Product::active()
            ->whereIn('id', $productIds)
            ->with(['category', 'brand', 'variants'])
            ->get()
            ->keyBy('id');
    }

    /**
     * Get compared product IDs
     */
    public function getComparedProductIds()
    {
        return Session::get(self::COMPARE_SESSION_KEY, []);
    }

    /**
     * Add product to comparison
     */
    public function addToComparison($productId)
    {
        $product = Product::active()->findOrFail($productId);

        $compared = $this->getComparedProductIds();

        // Check if already in comparison
        if (in_array($productId, $compared)) {
            return [
                'success' => true,
                'message' => 'Product already in comparison',
                'count' => count($compared),
                'max' => self::MAX_COMPARE_ITEMS
            ];
        }

        // Check max items limit
        if (count($compared) >= self::MAX_COMPARE_ITEMS) {
            return [
                'success' => false,
                'message' => 'Maximum ' . self::MAX_COMPARE_ITEMS . ' products can be compared',
                'count' => count($compared),
                'max' => self::MAX_COMPARE_ITEMS
            ];
        }

        $compared[] = $productId;
        Session::put(self::COMPARE_SESSION_KEY, $compared);

        return [
            'success' => true,
            'message' => 'Product added to comparison',
            'count' => count($compared),
            'max' => self::MAX_COMPARE_ITEMS,
            'product' => $product->name
        ];
    }

    /**
     * Remove product from comparison
     */
    public function removeFromComparison($productId)
    {
        $compared = $this->getComparedProductIds();

        if (!in_array($productId, $compared)) {
            return [
                'success' => false,
                'message' => 'Product not in comparison',
                'count' => count($compared),
                'max' => self::MAX_COMPARE_ITEMS
            ];
        }

        $compared = array_filter($compared, function ($id) use ($productId) {
            return $id != $productId;
        });

        Session::put(self::COMPARE_SESSION_KEY, array_values($compared));

        return [
            'success' => true,
            'message' => 'Product removed from comparison',
            'count' => count($compared),
            'max' => self::MAX_COMPARE_ITEMS
        ];
    }

    /**
     * Clear all compared products
     */
    public function clearComparison()
    {
        Session::forget(self::COMPARE_SESSION_KEY);

        return [
            'success' => true,
            'message' => 'Comparison cleared',
            'count' => 0,
            'max' => self::MAX_COMPARE_ITEMS
        ];
    }

    /**
     * Get comparison count
     */
    public function getComparisonCount()
    {
        return count($this->getComparedProductIds());
    }

    /**
     * Check if product is in comparison
     */
    public function isProductInComparison($productId)
    {
        return in_array($productId, $this->getComparedProductIds());
    }

    /**
     * Get comparison data with detailed attributes
     */
    public function getComparisonData()
    {
        $products = $this->getComparedProducts();

        if ($products->isEmpty()) {
            return [
                'products' => [],
                'attributes' => [],
                'isEmpty' => true
            ];
        }

        // Extract comparison attributes
        $attributes = [
            'brand' => ['label' => 'Brand', 'key' => 'brand'],
            'category' => ['label' => 'Category', 'key' => 'category'],
            'regular_price' => ['label' => 'Price', 'key' => 'regular_price'],
            'stock_qty' => ['label' => 'Stock', 'key' => 'stock_qty'],
            'description' => ['label' => 'Description', 'key' => 'description'],
            'color' => ['label' => 'Color', 'key' => 'color'],
            'weight' => ['label' => 'Weight', 'key' => 'weight'],
            'dimensions' => ['label' => 'Dimensions', 'key' => 'dimensions'],
        ];

        return [
            'products' => $products,
            'attributes' => $attributes,
            'isEmpty' => false,
            'count' => $products->count(),
            'max' => self::MAX_COMPARE_ITEMS
        ];
    }

    /**
     * Check if can add more items
     */
    public function canAddMore()
    {
        return $this->getComparisonCount() < self::MAX_COMPARE_ITEMS;
    }
}
