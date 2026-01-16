<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function createPermissions(array $data): void
    {
        if (isset($data['suffix']) && count($data['suffix']) > 0) {
            foreach ($data['suffix'] as $suffix) {
                Permission::create([
                    'name' => $data['name'] . ' ' . $suffix,
                    'status' => $data['status'] ?? 1,
                ]);
            }
        } else {
            Permission::create([
                'name' => $data['name'],
                'status' => $data['status'] ?? 1,
            ]);
        }
    }

    public function updatePermission(Permission $permission, array $data): bool
    {
        // Don't update name to something that exists? Request handles validation.
        // Also original logic doesn't support changing suffix on update, just name.
        return $permission->update($data);
    }

    public function deletePermission(Permission $permission): ?bool
    {
        return $permission->delete();
    }

    public function changeStatus(Permission $permission): bool
    {
        return $permission->update([
            'status' => !$permission->status
        ]);
    }
}
