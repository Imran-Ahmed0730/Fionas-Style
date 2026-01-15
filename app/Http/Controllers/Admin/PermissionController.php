<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | unique:permissions,name',
        ]);
        if(isset($request->suffix) && count($request->suffix) > 0){
            foreach ($request->suffix as $suffix)
            {
                Permission::create([
                    'name' => $request->name.' '.$suffix,
                    'status' => $request->status ?? 1,
                ]);
            }
        }
        else{
            Permission::create([
                'name' => $request->name,
                'status' => $request->status ?? 1,
            ]);
        }
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
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $permission = Permission::findOrFail($request->id);
        if($request->name != $permission->name){
            $request->validate([
                'name' => 'unique:permissions,name'
            ]);
        }
        $permission->update($request->all());
        return redirect()->route('admin.permission.index')->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Permission::where('id', $request->id)->delete();
        return redirect()->route('admin.permission.index')->with('success', 'Permission deleted successfully');
    }

    public function changeStatus($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['status' => !$permission->status]);
        return response()->json([
            'success' => true,
            'message'=> 'Permission status changed successfully',
        ]);
    }
}
