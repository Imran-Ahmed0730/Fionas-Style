<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'name' => 'Kg',
            'status' => 1
        ]);
    }
}
