<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Category;

class CategoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getFeaturedCategories()
    {
        return Cache::remember('featured_categories', config('cache_settings.long'), function () {
            return Category::active()
                ->where('is_featured', 1)
                ->orderBy('priority', 'asc')
                ->limit(3)
                ->get();
        });
    }

    /**
     * Get Category Wise Products (Sections)
     */
    public function getCategoryWiseProducts()
    {
        return Cache::remember('category_wise_products', config('cache_settings.long'), function () {
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
}
