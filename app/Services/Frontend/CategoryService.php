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
    public function getCategoryBySlug($slug)
    {
        return Cache::remember('category_' . $slug, config('cache_settings.long'), function () use ($slug) {
            return Category::active()
                ->where('slug', $slug)
                ->with([
                    'subcategories' => function ($query) {
                        $query->active()
                            ->orderBy('priority', 'asc');
                    }
                ])
                ->firstOrFail();
        });
    }
    public function getAllCategories()
    {
        return Cache::remember('all_categories_page', config('cache_settings.long'), function () {
            return Category::active()
                ->where('id', '!=', 1)
                ->where('parent_id', 0)
                ->with([
                    'subcategories' => function ($q) {
                        $q->active()->withCount('products');
                    }
                ])
                ->withCount('products')
                ->orderBy('priority', 'asc')
                ->get();
        });
    }
}
