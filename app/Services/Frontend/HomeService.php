<?php

namespace App\Services\Frontend;

use App\Models\Admin\Blog;
use App\Models\Admin\Slider;
use App\Models\Admin\Brand;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    /**
     * Get Hero Sliders
     */
    public function getHeroSliders()
    {

        return Cache::remember('hero_sliders', config('cache_settings.long'), function () {
            return Slider::active()->orderBy('priority', 'asc')->get();
        });
    }

    /**
     * Get Latest Blogs
     */
    public function getLatestBlogs()
    {
        return Cache::remember('latest_blogs', config('cache_settings.medium'), function () {
            return Blog::active()
                ->latest()
                ->limit(3)
                ->get();
        });
    }

    public function getBrands()
    {
        return Cache::remember('brands', config('cache_settings.medium'), function () {
            return Brand::active()->orderBy('name', 'asc')->get();
        });
    }

    /**
     * Get active campaigns (ongoing)
     */
    public function getActiveCampaigns()
    {
        return Cache::remember('active_campaigns', config('cache_settings.short'), function () {
            return \App\Models\Admin\Campaign::where('status', 1)
                ->with(['campaignProducts.product.category', 'category'])
                ->get()
                ->filter(function ($c) {
                    return $c->isActive;
                })->values();
        });
    }
}
