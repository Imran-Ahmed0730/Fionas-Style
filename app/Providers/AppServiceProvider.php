<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind("App\Interfaces\CategoryInterface", "App\Repositories\CategoryRepository");

        view()->composer('frontend.include.header', \App\View\Composers\CategoryComposer::class);
        view()->composer(['frontend.include.header', 'frontend.include.footer'], \App\View\Composers\SettingComposer::class);
    }
}
