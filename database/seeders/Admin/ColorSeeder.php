<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Color::create([
            'name' => 'White',
            'color_code' => '#fff'
        ]);
    }
}
