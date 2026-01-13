<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\HeaderComposer;
use App\View\Composers\FooterComposer;
use App\View\Composers\CategoryComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share header data with specific views
        View::composer(
            ['frontend.include.header', 'frontend.include.header'],
            HeaderComposer::class
        );

        // Share footer data
        View::composer(
            ['frontend.include.footer', 'frontend.include.footer'],
            FooterComposer::class
        );

        // Share categories with multiple views
        View::composer(
            ['frontend.include.header', 'frontend.include.sidebar'],
            CategoryComposer::class
        );

        // Share data with ALL views (use sparingly)
        View::composer('*', function ($view) {
            $view->with('currentYear', date('Y'));
        });

        // Share data with multiple views using wildcard
        View::composer('frontend.*', function ($view) {
            $view->with('appName', config('app.name'));
        });
    }
}
