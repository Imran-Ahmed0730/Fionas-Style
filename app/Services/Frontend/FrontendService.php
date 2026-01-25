<?php

namespace App\Services\Frontend;

use App\Models\Admin\Blog;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Slider;
use Illuminate\Support\Facades\Cache;

class FrontendService
{
    const CACHE_SHORT = 600;   // 10 minutes (Latest Products, etc.)
    const CACHE_MEDIUM = 3600;  // 1 hour (Featured, Blogs, etc.)
    const CACHE_LONG = 86400; // 24 hours (Sliders, Categories, etc.)

    /**
     * Get Hero Sliders
     */
    public function getHeroSliders()
    {
        return Cache::remember('hero_sliders', self::CACHE_LONG, function () {
            return Slider::active()->orderBy('priority', 'asc')->get();
        });
    }

    /**
     * Get Featured Products
     */
    public function getFeaturedProducts()
    {
        return Cache::remember('featured_products', self::CACHE_MEDIUM, function () {
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
        return Cache::remember('todays_deal_products', self::CACHE_MEDIUM, function () {
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

    public function getFeaturedCategories()
    {
        return Cache::remember('featured_categories', self::CACHE_LONG, function () {
            return Category::active()
                ->where('is_featured', 1)
                ->orderBy('priority', 'asc')
                ->limit(3)
                ->get();
        });
    }

    /**
     * Get Latest Products
     */
    public function getLatestProducts()
    {
        return Cache::remember('latest_products', self::CACHE_SHORT, function () {
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

    /**
     * Get Category Wise Products (Sections)
     */
    public function getCategoryWiseProducts()
    {
        return Cache::remember('category_wise_products', self::CACHE_LONG, function () {
            return Category::active()
                ->where('included_to_home', 1)
                ->with([
                    'products' => function ($query) {
                        $query->active()
                            ->where('stock_qty', '>', 0)
                            ->with(['category', 'campaignProducts.campaign'])
                            ->latest()
                            ->limit(8);
                    }
                ])
                ->orderBy('priority', 'asc')
                ->limit(3)
                ->get();
        });
    }

    /**
     * Get Latest Blogs
     */
    public function getLatestBlogs()
    {
        return Cache::remember('latest_blogs', self::CACHE_MEDIUM, function () {
            return Blog::active()
                ->latest()
                ->limit(3)
                ->get();
        });
    }
}
