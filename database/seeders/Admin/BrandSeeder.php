<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brand = Brand::create([
            'name' => 'Non Brand',
            'description' => 'Non Brand',
            'image' => 'backend/assets/img/non-brand.jpg',
            'status' => 1,
        ]);

    }
}
