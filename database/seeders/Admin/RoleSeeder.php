<?php

namespace Database\Seeders\Admin;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_role = Role::create(['name' => 'Admin']);
        $super_admin_role = Role::create(['name' => 'Super Admin']);
        $permissions = Permission::latest()->pluck('name')->toArray();
        $super_admin_role->syncPermissions($permissions);

        $admin = User::where('name', 'Admin')->first();
        $super_admin = User::where('name', 'Super Admin')->first();
        $admin->assignRole($admin_role->id);
        $super_admin->assignRole($super_admin_role->id);
    }
}
