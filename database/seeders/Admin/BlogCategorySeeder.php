<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\Admin\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCategory::create([
            'name' => 'Unknown',
            'slug' => 'unknown',
            'description' => 'Unknown Category',
            'meta_title' => 'Unknown',
            'meta_description' => 'Unknown',
            'meta_keywords' => 'unknown',
            'meta_image' => null,
            'status' => 1,
        ]);
    }
}
