<?php

use App\Http\Controllers\Frontend\Customer\CustomerController;
use App\Http\Controllers\Frontend\Customer\WishlistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/order/{invoice}', 'orderDetails')->name('order.details');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile/update', 'updateProfile')->name('profile.update');
        Route::get('/get-states/{countryId}', 'getStates')->name('get-states');
        Route::get('/get-cities/{stateId}', 'getCities')->name('get-cities');
        Route::delete('/delete-account', 'destroy')->name('account.delete');
    });

    Route::controller(WishlistController::class)->prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add', 'store')->name('store');
        Route::delete('/remove/{id}', 'destroy')->name('destroy');
    });
});