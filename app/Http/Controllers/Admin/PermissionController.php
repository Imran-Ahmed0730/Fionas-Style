<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Admin\PermissionRequest;
use App\Services\PermissionService;

class PermissionController extends Controller implements HasMiddleware
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    public static function middleware():array
    {
        return [
            new Middleware('permission:Permission Add',only: ['create']),
            new Middleware('permission:Permission View',only: ['index']),
            new Middleware('permission:Permission Update',only: ['edit']),
            new Middleware('permission:Permission Delete',only: ['destroy']),
            new Middleware('permission:Permission Status Change',only: ['changeStatus']),
        ];
    }
    public function index()
    {
        $data['items'] = Permission::all();
        return view('backend.role-permission.permission.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.role-permission.permission.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $this->permissionService->createPermissions($request->validated());
        return redirect()->route('admin.permission.index')->with('success', 'Permission created successfully');
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
        $data['item'] = Permission::findOrFail($id);
        return view('backend.role-permission.permission.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request)
    {
        $permission = Permission::findOrFail($request->id);
        $this->permissionService->updatePermission($permission, $request->validated());
        return redirect()->route('admin.permission.index')->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Using findOrFail as consistent with other methods, although original used where->delete
        $permission = Permission::findOrFail($request->id);
        $this->permissionService->deletePermission($permission);
        return redirect()->route('admin.permission.index')->with('success', 'Permission deleted successfully');
    }

    public function changeStatus($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permissionService->changeStatus($permission);
        return response()->json([
            'success' => true,
            'message'=> 'Permission status changed successfully',
        ]);
    }
}
