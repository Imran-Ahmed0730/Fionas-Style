<?php

namespace App\Services\Admin;

use App\Models\Admin\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function createRole(array $data): Role
    {
        return Role::create($data);
    }

    public function updateRole(Role $role, array $data): bool
    {
         return $role->update($data);
    }

    public function deleteRole(int $id): int
    {
        return Role::destroy($id);
    }

    public function getPermissionsForUser()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return Permission::orderBy('name', 'asc')->get();
        } else {
            return Permission::active()->orderBy('name', 'asc')->get();
        }
    }

    public function getRolePermissions(int $roleId): array
    {
        return DB::table('role_has_permissions')->where('role_id', $roleId)->pluck('permission_id')->toArray();
    }

    public function syncPermissions(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
    }
}
