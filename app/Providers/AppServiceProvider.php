<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\CategoryComposer;
use App\View\Composers\SettingComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFour();
        $this->app->bind("App\Interfaces\CategoryInterface", "App\Repositories\CategoryRepository");

        view()->composer('frontend.include.header', CategoryComposer::class);
        view()->composer(['frontend.include.header', 'frontend.include.footer'], SettingComposer::class);
    }
}
