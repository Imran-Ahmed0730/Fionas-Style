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

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([AdminSeeder::class]);
        $this->call([PermissionSeeder::class]);
        $this->call([RoleSeeder::class]);
        $this->call([SettingSeeder::class]);
        $this->call([CategorySeeder::class]);
        $this->call([BrandSeeder::class]);
        $this->call([UnitSeeder::class]);
        $this->call([ColorSeeder::class]);
        $this->call([AttributeSeeder::class]);
        $this->call([SupplierSeeder::class]);
    }
}
