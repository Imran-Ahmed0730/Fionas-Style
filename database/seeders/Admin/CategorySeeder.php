<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'General',
            'description' => null,
            'slug' => 'general',
            'icon' => null,
            'cover_photo' => null,
            'parent_id' => 0,
            'priority' => 0,
            'level' => 1,
            'status' => 1,

        ]);
    }
}
