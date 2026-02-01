<?php
use App\Http\Controllers\Frontend\Customer\CustomerController;
use App\Http\Controllers\Frontend\Customer\WishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->name('customer.')->group(function () {
   Route::controller(CustomerController::class)->group(function(){
    Route::get('/sign-in', 'signIn')->name('sign-in');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::controller(WishlistController::class)->prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });
   });
});