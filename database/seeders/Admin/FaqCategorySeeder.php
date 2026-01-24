<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\FaqCategory;
use Illuminate\Database\Seeder;

class FaqCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FaqCategory::create([
            'name' => 'Unknown',
            'status' => 1,
        ]);
    }
}
