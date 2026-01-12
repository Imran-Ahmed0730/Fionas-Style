<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attribute::create([
            'name' => 'Size',
            'status' => 1
        ]);
    }
}
