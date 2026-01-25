<?php

namespace App\View\Composers;


use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Category;

class CategoryComposer
{
    public function compose(View $view)
    {
        $categories = Cache::remember('main_categories', 3600, function () {
            return
                Category::where('status', 1)
                    ->orderBy('priority', 'asc')
                    ->limit(10)
                    ->get(['id', 'name', 'slug'])
            ;
        });

        $view->with('categories', $categories);
    }
}
