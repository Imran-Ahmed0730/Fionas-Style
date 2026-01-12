<?php

namespace Database\Seeders\Admin;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@gmail.com',
            'role'      => 1,
            'password'  => Hash::make('12345678'),
        ]);
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@gmail.com',
            'role'      => 1,
            'password'  => Hash::make('12345678'),
        ]);
    }
}
