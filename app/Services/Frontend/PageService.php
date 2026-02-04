<?php

namespace App\Services\Frontend;

use App\Models\Admin\Page;
use Illuminate\Support\Facades\Cache;

class PageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getPageBySlug($slug)
    {
        return Cache::remember('page_' . $slug, config('cache_settings.long'), function () use ($slug) {
            return Page::active()->where('slug', $slug)->firstOrFail();
        });
    }
}
