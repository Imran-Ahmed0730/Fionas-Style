<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'name' => 'Rahman',
            'email' => 'rahman@gmail.com',
            'phone' => '01234567890',
            'address' => 'Dhaka',
            'nid' => '1234567890'
        ]);
    }
}
