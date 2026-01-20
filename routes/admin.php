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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductStockController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CustomerController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::middleware('admin')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/profile/edit', 'profileEdit')->name('profile.edit');
            Route::post('/profile/update', 'profileUpdate')->name('profile.update');
            Route::post('/password/update', 'passwordChange')->name('password.update');
        });

        //setting module
        Route::controller(SettingController::class)->prefix('setting')->name('setting.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/list', 'index')->name('index');
            Route::get('/edit/{slug}', 'goToSection')->name('edit');
            Route::post('/language/remove', 'removeLanguage')->name('language.remove');
            Route::post('/update', 'update')->name('update');
            Route::post('/update-fields', 'updateFields')->name('update-fields');
        });

        //role module
        Route::resource('role', RoleController::class)->except('show', 'destroy', 'update');
        Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/accessibility/assign/{id}', 'assignPermission')->name('accessibility.assign');
            Route::post('/permission/assign', 'assignPermissionSubmit')->name('permission.assign.submit');
        });

        //permission module
        Route::resource('permission', PermissionController::class)->except('show', 'destroy', 'update');
        Route::controller(PermissionController::class)->prefix('permission')->name('permission.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //staff module
        Route::resource('staff', StaffController::class)->except('show', 'destroy', 'update');
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::controller(StaffController::class)->group(function () {
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'destroy')->name('delete');
                Route::get('/status/change/{id}', 'changeStatus')->name('status.change');

            });
            Route::controller(AdminController::class)->group(function () {
                Route::get('/role/assign', 'roleAssign')->name('assign');
                Route::post('/role/assign/submit', 'roleAssignSubmit')->name('role.assign.submit');
            });
        });

        //category module
        Route::resource('category', CategoryController::class)->except('show', 'destroy', 'update');
        Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
            Route::get('/include/home/{id}', 'homeInclude')->name('include.home');
        });

        //brand module
        Route::resource('brand', BrandController::class)->except('show', 'destroy', 'update');
        Route::controller(BrandController::class)->prefix('brand')->name('brand.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //unit module
        Route::resource('unit', UnitController::class)->except('show', 'destroy', 'update');
        Route::controller(UnitController::class)->prefix('unit')->name('unit.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //supplier module
        Route::resource('supplier', SupplierController::class)->except('show', 'destroy', 'update');
        Route::controller(SupplierController::class)->prefix('supplier')->name('supplier.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //color module
        Route::resource('color', ColorController::class)->except('show', 'destroy', 'update');
        Route::controller(ColorController::class)->prefix('color')->name('color.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
        });

        //attribute module
        Route::resource('attribute', AttributeController::class)->except('show', 'destroy', 'update');
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

        //product module
        Route::resource('product', ProductController::class)->except('destroy', 'update');
        Route::prefix('product')->name('product.')->group(function () {
            Route::controller(ProductController::class)->group(function () {
                Route::get('/sku/generate', 'generateSku')->name('sku.generate');
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'destroy')->name('delete');
                Route::get('/attribute/values/get', 'getAttributeValues')->name('attribute.values.get');
                Route::get('/image/delete', 'deleteImage')->name('image.delete');
                Route::get('/variant/delete', 'deleteVariant')->name('variant.delete');
                Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
                Route::get('/todays-deal/change/{id}', 'changeTodaysDealStatus')->name('todays-deal.change');
                Route::get('/featured/change/{id}', 'changeFeaturedStatus')->name('featured.change');
            });
        });

        //stock module
        Route::resource('stock', ProductStockController::class)->except('show', 'edit', 'update', 'destroy');
        Route::controller(ProductStockController::class)->prefix('stock')->name('stock.')->group(function () {
            Route::get('/sku/generate', 'generateSku')->name('sku.generate');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/get-product', 'getProduct')->name('get-product');
        });

        //coupon module
        Route::resource('coupon', CouponController::class)->except('edit', 'update', 'destroy');
        Route::controller(CouponController::class)->prefix('coupon')->name('coupon.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //customer module
        Route::resource('customer', CustomerController::class)->except('update', 'destroy');
        Route::controller(CustomerController::class)->prefix('customer')->name('customer.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //campaign module
        Route::resource('campaign', CampaignController::class)->except('edit', 'update', 'destroy');
        Route::controller(CampaignController::class)->prefix('campaign')->name('campaign.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });


        //banner module
        Route::resource('banner', BannerController::class)->except('show', 'destroy', 'update');
        Route::controller(BannerController::class)->prefix('banner')->name('banner.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //slider module
        Route::resource('slider', SliderController::class)->except('show', 'destroy', 'update');
        Route::controller(SliderController::class)->prefix('slider')->name('slider.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

        //page module
        Route::resource('page', PageController::class)->except('show', 'destroy', 'update');
        Route::controller(PageController::class)->prefix('page')->name('page.')->group(function () {
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
            Route::get('/status/change/{id}', 'changeStatus')->name('status.change');
        });

    });
});
