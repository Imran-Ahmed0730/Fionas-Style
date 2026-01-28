<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Admin\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->group(base_path('routes/admin.php'));

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/lifestyle', 'lifestyle')->name('lifestyle');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category/{slug}', 'category')->name('category');
    Route::get('/categories', 'allCategories')->name('categories');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop');
    Route::get('/product/{slug}', 'productDetail')->name('product.details');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/blogs', 'index')->name('blogs');
    Route::get('/blog/{slug}', 'blogDetail')->name('blog.details');
});

Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');

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
