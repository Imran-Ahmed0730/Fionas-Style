<?php
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CompareController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CampaignController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

// Google Auth Routes
Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::middleware('web')
    ->group(base_path('routes/admin.php'));

Route::middleware('web')
    ->group(base_path('routes/customer.php'));

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/lifestyle', 'lifestyle')->name('lifestyle');
    Route::get('/about-us', 'aboutUs')->name('page.about');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/order/track', 'orderTrackForm')->name('order.track');
    Route::post('/order/track', 'orderTrackSubmit')->name('order.track.submit');
    Route::get('/privacy-policy', 'privacyPolicy')->name('page.privacy');
    Route::get('/terms-conditions', 'termsConditions')->name('page.terms');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category/{slug}', 'category')->name('category');
    Route::get('/categories', 'index')->name('categories');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop');
    Route::get('/search', 'search')->name('search');
    Route::get('/product/{slug}', 'show')->name('product.show');
    Route::get('/product/quick-view/{id}', 'quickView')->name('product.quickView');
    Route::get('/get-variant', 'getVariant')->name('product.getVariant');
});

Route::controller(CampaignController::class)->group(function () {
    Route::get('/campaigns', 'index')->name('campaign.index');
    Route::get('/campaign/{slug}', 'show')->name('campaign.show');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/blogs', 'index')->name('blog.index');
    Route::get('/blog/{slug}', 'blogDetail')->name('blog.details');
});


Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/add', 'cartAdd')->name('add');
    Route::post('/update', 'update')->name('update');
    Route::get('/remove', 'cartRemove')->name('remove');

});

Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');

Route::controller(CompareController::class)->prefix('compare')->name('compare.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/add', 'add')->name('add');
    Route::post('/remove', 'remove')->name('remove');
    Route::post('/clear', 'clear')->name('clear');
    Route::get('/count', 'getCount')->name('count');
    Route::post('/is-in-comparison', 'isInComparison')->name('is.in.comparison');
});

Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/get-states', 'getStates')->name('get.states');
    Route::get('/get-cities', 'getCities')->name('get.cities');
    Route::post('/apply-coupon', 'applyCoupon')->name('apply.coupon');
    Route::get('/remove-coupon', 'removeCoupon')->name('remove.coupon');
    Route::post('/place-order', 'placeOrder')->name('place.order');
    Route::get('/summary/{invoice}', 'orderSummary')->name('summary');
});

/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        // Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', 'update')->name('update');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });
});

require __DIR__ . '/auth.php';
