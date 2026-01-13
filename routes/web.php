<?php

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->group(base_path('routes/admin.php'));

Route::controller(FrontendController::class)->group(function () {
   Route::get('/', 'index')->name('home');
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

require __DIR__.'/auth.php';
