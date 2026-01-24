<?php

namespace App\Services\Frontend;

use App\Models\Admin\Banner;
use App\Models\Admin\Blog;
use App\Models\Admin\Brand;
use App\Models\Admin\Campaign;
use App\Models\Admin\Category;
use App\Models\Admin\Faq;
use App\Models\Admin\Product;
use App\Models\Admin\Slider;
use Illuminate\Support\Facades\Cache;

class FrontendService
{
    // Cache Durations (in seconds)
    const CACHE_SHORT = 1800; // 30 minutes
    const CACHE_MEDIUM = 3600; // 1 hour
    const CACHE_LONG = 86400; // 24 hours

    /**
     * Get Active Sliders (Cached)
     */
    public function getSliders()
    {
        return Cache::remember('sliders_active', self::CACHE_MEDIUM, function () {
            return Slider::where('status', 1)->orderBy('id', 'desc')->get();
        });
    }

    /**
     * Get Active Banners (Cached)
     */
    public function getBanners()
    {
        return Cache::remember('banners_active', self::CACHE_MEDIUM, function () {
            return Banner::where('status', 1)->latest()->get();
        });
    }

    /**
     * Get Featured Categories (Cached)
     */
    public function getFeaturedCategories()
    {
        return Cache::remember('categories_featured', self::CACHE_LONG, function () {
            // Assuming we might have a 'is_featured' flag or just take random/latest
            return Category::where('status', 1)->where('is_featured', 1)->limit(6)->get();
        });
    }

    /**
     * Get New Arrival Products (Cached)
     */
    public function getNewArrivals()
    {
        return Cache::remember('products_new_arrival', self::CACHE_SHORT, function () {
            return Product::with(['category', 'brand', 'gallery']) // Eager Loading
            ->whereHas('stocks', function ($query) {
                $query->where('quantity', '>', 0);
            })
                ->where('status', 1)
                ->latest()
                ->limit(8)
                ->get();
        });
    }

    /**
     * Get Active Campaigns (Cached)
     */
    public function getCampaigns()
    {
        return Cache::remember('campaigns_active', self::CACHE_SHORT, function () {
            $campaigns = Campaign::where('status', 1)->get();
            return $campaigns->filter(function ($campaign) {
                return $campaign->is_active;
            });
        });
    }

    /**
     * Get Latest Blogs (Cached)
     */
    public function getLatestBlogs()
    {
        return Cache::remember('blogs_latest', self::CACHE_MEDIUM, function () {
            return Blog::with('category')
                ->where('status', 1)
                ->latest()
                ->limit(4)
                ->get();
        });
    }
}
