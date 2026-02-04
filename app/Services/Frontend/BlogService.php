<?php

namespace App\Services\Frontend;

use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use Illuminate\Support\Facades\Cache;

class BlogService
{
    /**
     * Get Paginated Blogs
     */
    public function getBlogs($perPage = 6, $categorySlug = null)
    {
        $page = request()->get('page', 1);
        $cacheKey = 'blogs_page_' . $page . '_' . $perPage . ($categorySlug ? '_cat_' . $categorySlug : '');

        return Cache::remember($cacheKey, config('cache_settings.medium'), function () use ($perPage, $categorySlug) {
            $query = Blog::active()->with('category');

            if ($categorySlug) {
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }

            return $query->latest()->paginate($perPage);
        });
    }

    /**
     * Get Blog by Slug
     */
    public function getBlogBySlug($slug)
    {
        return Cache::remember('blog_' . $slug, config('cache_settings.long'), function () use ($slug) {
            return Blog::active()
                ->with('category')
                ->where('slug', $slug)
                ->firstOrFail();
        });
    }

    /**
     * Get Recent Blogs
     */
    public function getRecentBlogs($limit = 3)
    {
        return Cache::remember('recent_blogs_' . $limit, config('cache_settings.medium'), function () use ($limit) {
            return Blog::active()
                ->latest()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get Blog Categories with Count
     */
    public function getBlogCategories()
    {
        return Cache::remember('blog_categories_count', config('cache_settings.long'), function () {
            return BlogCategory::withCount([
                'blogs' => function ($query) {
                    $query->active();
                }
            ])->get();
        });
    }
}
