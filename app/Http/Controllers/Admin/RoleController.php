<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;

class RoleController extends Controller implements HasMiddleware
{
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        $role = Role::create($request->all());
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
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $role = Role::findOrFail($request->id);
        if ($request->name != $role->name) {
            $request->validate([
                'name' => 'unique:roles,name'
            ]);
        }
        $role->update($request->all());
        return redirect()->route('admin.role.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Role::destroy($request->id);
        return redirect()->route('admin.role.index')->with('success', 'Role deleted successfully');
    }

    public function assignPermission($id)
    {
        $data['role'] = Role::findOrFail($id);
        if(Auth::user()->hasRole('Super Admin')) {
            $data['permissions'] = Permission::orderBy('name', 'asc')->get();
        }
        else{
            $data['permissions'] = Permission::where('status', 1)->orderBy('name', 'asc')->get();
        }
        $data['permissionsGrouped'] = $data['permissions']->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0]; // Get the part before the first hyphen
        });
        $data['items'] = DB::table('role_has_permissions')->where('role_id', $id)->pluck('permission_id')->toArray();
        return view('backend.role-permission.form', $data);
    }

    public function assignPermissionSubmit(Request $request)
    {
//        return $request;
        $request->validate([
            'permission' => 'required | array',
        ]);
//        dd($request->permission);
        $role = Role::findOrFail($request->id);
        $role->syncPermissions($request->permission);
        return redirect()->route('admin.role.index')->with('success', 'Permission assigned to the role successfully');
    }
}
