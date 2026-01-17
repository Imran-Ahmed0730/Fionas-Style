<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    public static function middleware(): array
    {
        return[
            new Middleware('permission:Role Add', only : ['create']),
            new Middleware('permission:Role View', only : ['index']),
            new Middleware('permission:Role Update', only : ['edit']),
            new Middleware('permission:Role Delete', only : ['destroy']),
            new Middleware('permission:Role Permission Add/Update', only : ['assignPermission']),
        ];
    }
    public function index()
    {
        $data['items'] = Role::all();
        return view('backend.role-permission.role.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.role-permission.role.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $this->roleService->createRole($request->validated());
        return redirect()->route('admin.role.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['item'] = Role::findOrFail($id);
        return view('backend.role-permission.role.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request)
    {
        $role = Role::findOrFail($request->id);
        $this->roleService->updateRole($role, $request->validated());
        return redirect()->route('admin.role.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->roleService->deleteRole($request->id);
        return redirect()->route('admin.role.index')->with('success', 'Role deleted successfully');
    }

    public function assignPermission($id)
    {
        $data['role'] = Role::findOrFail($id);
        $data['permissions'] = $this->roleService->getPermissionsForUser();
        $data['permissionsGrouped'] = $data['permissions']->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0]; // Get the part before the first hyphen
        });
        $data['items'] = $this->roleService->getRolePermissions($id);
        return view('backend.role-permission.form', $data);
    }

    public function assignPermissionSubmit(RoleRequest $request)
    {
        $role = Role::findOrFail($request->id);
        $this->roleService->syncPermissions($role, $request->permission);
        return redirect()->route('admin.role.index')->with('success', 'Permission assigned to the role successfully');
    }
}
