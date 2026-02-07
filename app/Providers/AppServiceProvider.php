<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\CategoryComposer;
use App\View\Composers\SettingComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Order;
use App\Models\Admin\OrderPayment;
use App\Models\Admin\ProductStock;
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

        // Invalidate admin dashboard cache when related models change
        $clear = function ($model = null) {
            Cache::forget('admin.dashboard.data');
        };

        Order::saved($clear);
        Order::deleted($clear);

        OrderPayment::saved($clear);
        OrderPayment::deleted($clear);

        ProductStock::saved($clear);
        ProductStock::deleted($clear);
    }
}
