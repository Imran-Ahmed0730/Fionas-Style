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
        $admin_role = Role::firstOrCreate(['name' => 'Admin']);
        $super_admin_role = Role::firstOrCreate(['name' => 'Super Admin']);

        $permissions = Permission::all()->pluck('name')->toArray();
        $super_admin_role->syncPermissions($permissions);

        $admin = User::where('email', 'admin@gmail.com')->first();
        $super_admin = User::where('email', 'superadmin@gmail.com')->first();

        if ($admin) {
            $admin->assignRole($admin_role);
        }
        if ($super_admin) {
            $super_admin->assignRole($super_admin_role);
        }
    }
}
