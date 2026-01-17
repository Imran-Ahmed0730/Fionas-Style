<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffRequest;
use App\Models\Admin\Staff;
use App\Services\Admin\StaffService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class StaffController extends Controller implements HasMiddleware
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Staff Create', only: ['create']),
            new Middleware('permission:Staff View', only: ['index']),
            new Middleware('permission:Staff Update', only: ['edit']),
            new Middleware('permission:Staff Delete', only: ['destroy']),
            new Middleware('permission:Staff Status Change', only: ['changeStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Staff::latest()->get();
        return view('backend.role-permission.staff.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['roles'] = Role::orderBy('name', 'asc')->get();
        return view('backend.role-permission.staff.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffRequest $request)
    {
        $this->staffService->createStaff($request->validated(), $request->file('image'));
        return redirect()->route('admin.staff.index')->with('success', 'Staff added successfully.');
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
        $data['roles'] = Role::orderBy('name', 'asc')->get();
        $data['item'] = Staff::where('id', $id)->first();
        return view('backend.role-permission.staff.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StaffRequest $request)
    {
        $staff = Staff::findOrFail($request->id);
        $this->staffService->updateStaff($staff, $request->validated(), $request->file('image'));
        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->staffService->deleteStaff($request->id);
        return back()->with('success', 'Staff deleted successfully.');
    }

    public function changeStatus($id)
    {
        $this->staffService->changeStatus($id);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}

