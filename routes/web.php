<?php
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CompareController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Admin\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->group(base_path('routes/admin.php'));

Route::middleware('web')
    ->group(base_path('routes/customer.php'));

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/lifestyle', 'lifestyle')->name('lifestyle');
    Route::get('/about', 'about')->name('page.about');
    Route::get('/contact', 'contact')->name('contact');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category/{slug}', 'category')->name('category');
    Route::get('/categories', 'index')->name('categories');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop');
    Route::get('/product/{slug}', 'show')->name('product.show');
    Route::get('/product/quick-view/{id}', 'quickView')->name('product.quickView');
    Route::get('/get-variant', 'getVariant')->name('product.getVariant');
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
    Route::post('/update-check-status', 'updateCheckedStatus')->name('update.check.status');
});

Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');

Route::controller(CompareController::class)->prefix('compare')->name('compare.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/add', 'add')->name('add');
    Route::get('/remove', 'remove')->name('remove');
});

Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/add', 'add')->name('add');
    Route::get('/remove', 'remove')->name('remove');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::patch('/update', 'update')->name('update');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });
});

require __DIR__ . '/auth.php';
