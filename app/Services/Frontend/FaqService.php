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
                ->with([
                    'faqs' => function ($query) {
                        $query->active()->orderBy('priority', 'asc');
                    }
                ])
                ->orderBy('priority', 'asc') // Assuming there is a priority field
                ->orderBy('name', 'asc')
                ->get();
        });
    }
}
