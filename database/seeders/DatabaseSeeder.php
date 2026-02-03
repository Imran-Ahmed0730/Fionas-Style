<?php

namespace Database\Seeders;

use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\AttributeSeeder;
use Database\Seeders\Admin\BrandSeeder;
use Database\Seeders\Admin\CategorySeeder;
use Database\Seeders\Admin\ColorSeeder;
use Database\Seeders\Admin\PermissionSeeder;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Admin\SettingSeeder;
use Database\Seeders\Admin\StaffSeeder;
use Database\Seeders\Admin\SupplierSeeder;
use Database\Seeders\Admin\UnitSeeder;
use Database\Seeders\Vendor\VendorSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\Admin\BlogCategorySeeder;
use Database\Seeders\Admin\BlogSeeder;
use Database\Seeders\Admin\FaqCategorySeeder;
use Database\Seeders\Admin\FaqSeeder;
use Database\Seeders\AccountHeadSeeder;
use Database\Seeders\Admin\PaymentMethodSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([AdminSeeder::class]);
        // $this->call([PermissionSeeder::class]);
        // $this->call([RoleSeeder::class]);
        // $this->call([SettingSeeder::class]);
        // $this->call([CategorySeeder::class]);
        // $this->call([BrandSeeder::class]);
        // $this->call([UnitSeeder::class]);
        // $this->call([ColorSeeder::class]);
        // $this->call([AttributeSeeder::class]);
        // $this->call([SupplierSeeder::class]);
        // $this->call([BlogCategorySeeder::class]);
        // $this->call([FaqCategorySeeder::class]);
        $this->call([AccountHeadSeeder::class]);
        $this->call([PaymentMethodSeeder::class]);
    }
}