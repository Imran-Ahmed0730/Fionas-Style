<?php

namespace App\Services\Frontend;

use App\Models\Admin\FaqCategory;
use Illuminate\Support\Facades\Cache;

class FaqService
{
    /**
     * Get All FAQ Categories with their FAQs
     */
    public function getFaqs()
    {
        return Cache::remember('faqs_list', config('cache_settings.long'), function () {
            return FaqCategory::active()
                ->where('id', '!=', 1) // Exclude "General" category
                ->with([
                    'faqs' => function ($query) {
                        $query->active()->latest()->get();
                    }
                ])
                ->orderBy('name', 'asc')
                ->get();
        });
    }
}
