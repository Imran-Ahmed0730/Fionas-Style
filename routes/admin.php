<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Vendor\VendorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::middleware('admin')->group(function (){
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [AdminController::class, 'profileEdit'])->name('edit');
            Route::post('/update', [AdminController::class, 'profileUpdate'])->name('update');
        });
        Route::post('password/update', [AdminController::class, 'passwordChange'])->name('password.update');

        //setting module
        Route::controller(SettingController::class)->prefix('setting')->name('setting.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/list', 'index')->name('index');
            Route::get('/edit/{slug}', 'goToSection')->name('edit');
            Route::post('/language/remove', 'removeLanguage')->name('language.remove');
            Route::post('/update', 'update')->name('update');
        });

        //role module
        Route::resource('role', RoleController::class);
        Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/accessibility/assign/{id}', 'assignPermission')->name('accessibility.assign');
            Route::post('/permission/assign', 'assignPermissionSubmit')->name('permission.assign.submit');
        });

        //permission module
        Route::resource('permission', PermissionController::class);
        Route::controller(PermissionController::class)->prefix('permission')->name('permission.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
        });

        //staff module
        Route::resource('staff', StaffController::class);
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::controller(StaffController::class)->group(function () {
                Route::post('/update','update')->name('update');
                Route::post('/delete','destroy')->name('delete');
                Route::get('/status/change/{id}',  'changeStatus')->name('status.change');

            });
            Route::controller(AdminController::class)->group(function () {
                Route::get('/role/assign', 'roleAssign')->name('assign');
                Route::post('/role/assign/submit', 'roleAssignSubmit')->name('role.assign.submit');
            });
        });

        //category module
        Route::resource('category', CategoryController::class);
        Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
            Route::get('/include/home/{id}',  'homeInclude')->name('include.home');
        });

        //brand module
        Route::resource('brand', BrandController::class);
        Route::controller(BrandController::class)->prefix('brand')->name('brand.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
        });

        //unit module
        Route::resource('unit', UnitController::class);
        Route::controller(UnitController::class)->prefix('unit')->name('unit.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
        });

        //supplier module
        Route::resource('supplier', SupplierController::class);
        Route::controller(SupplierController::class)->prefix('supplier')->name('supplier.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
        });

        //color module
        Route::resource('color', ColorController::class);
        Route::controller(ColorController::class)->prefix('color')->name('color.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
        });

        //attribute module
        Route::resource('attribute', AttributeController::class);
        Route::prefix('attribute')->name('attribute.')->group(function () {
            Route::controller(AttributeController::class)->group(function () {
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'destroy')->name('delete');
                Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
            });
            Route::controller(AttributeValueController::class)->prefix('value')->name('value.')->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::get('/{id}', 'edit')->name('edit');
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'destroy')->name('delete');
                Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
            });
        });

        //vendor module
        Route::resource('vendor', VendorController::class);
        Route::controller(VendorController::class)->prefix('vendor')->name('vendor.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
        });

        //banner module
        Route::resource('banner', BannerController::class);
        Route::controller(BannerController::class)->prefix('banner')->name('banner.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
        });

        //slider module
        Route::resource('slider', SliderController::class);
        Route::controller(SliderController::class)->prefix('slider')->name('slider.')->group(function () {
            Route::post('/update',  'update')->name('update');
            Route::post('/delete',  'destroy')->name('delete');
            Route::get('/status/change/{id}',  'changeStatus')->name('status.change');
        });

    });
});
