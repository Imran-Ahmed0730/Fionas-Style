<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Product;

class ProductService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get Featured Products
     */
    public function getFeaturedProducts()
    {
        return Cache::remember('featured_products', config('cache_settings.medium'), function () {
            return Product::active()
                ->where('is_featured', 1)
                ->where('stock_qty', '>', 0)
                ->whereHas('category', function ($query) {
                    $query->active();
                })
                ->with(['category', 'gallery', 'campaignProducts.campaign'])
                ->latest()
                ->get();
        });
    }

    /**
     * Get Today's Deal Products
     */
    public function getTodaysDealProducts()
    {
        return Cache::remember('todays_deal_products', config('cache_settings.medium'), function () {
            return Product::active()
                ->where('include_to_todays_deal', 1)
                ->where('stock_qty', '>', 0)
                ->whereHas('category', function ($query) {
                    $query->active();
                })
                ->with(['category', 'gallery', 'campaignProducts.campaign'])
                ->latest()
                ->limit(10)
                ->get();
        });
    }

    /**
     * Get Latest Products
     */
    public function getLatestProducts()
    {
        return Cache::remember('latest_products', config('cache_settings.short'), function () {
            return Product::active()
                ->where('stock_qty', '>', 0)
                ->whereHas('category', function ($query) {
                    $query->active();
                })
                ->with(['category', 'campaignProducts.campaign'])
                ->latest()
                ->limit(20)
                ->get();
        });
    }
}
