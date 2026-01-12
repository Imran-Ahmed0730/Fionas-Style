<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffController extends Controller implements HasMiddleware
{
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'email|required|unique:staff',
            'phone' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'required',
            'salary' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        if(isset($request->nid_no)){
            $request->validate([
                'nid_no' => 'unique:staff',
            ]);
        }
        $image = $request->file('image');
        $imagePath = saveImagePath($image, 'staff');

        $user = User::create([
            'name'=> $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'image' => $imagePath,
            'role' => 4,
        ]);

        Staff::create([
            'user_id' => $user->id,
            'name'=> $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imagePath,
            'password' => Hash::make($request->password),
            'salary' => $request->salary,
            'nid_no' => $request->nid_no,
            'join_date' => $request->join_date ?? date('Y-m-d'),
            'status' => $request->status ?? 0,
        ]);

        $user->syncRoles($request->role);

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
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'email|required',
            'phone' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'required',
            'salary' => 'required',
        ]);
        if ($request->password){
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
        }
        $staff = Staff::findOrFail($request->id);
        $user = User::find($staff->user_id);
        if(isset($request->nid_no)){
            if($request->nid_no != $staff->nid_no){
                $request->validate([
                    'nid_no' => 'unique:staff',
                ]);
            }
        }
        if ($request->email != $staff->email) {
            $request->validate([
                'email' => 'unique:staff',
            ]);
        }
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $imagePath = updateImagePath($image, $staff->image, 'staff' );

        }
        else{
            $imagePath = $staff->image;
        }


        $user->update([
            'name'=> $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imagePath,
        ]);

        $staff->update([
            'name'=> $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imagePath,
            'salary' => $request->salary,
            'nid_no' => $request->nid_no,
            'join_date' => $request->join_date ?? date('Y-m-d'),
            'status' => $request->status ?? 0,
        ]);

        if ($request->password != null){
            $staff->password = Hash::make($request->password);
            $staff->save();

            $user->password = Hash::make($request->password);
            $user->save();
        }

        $user->syncRoles($request->role);

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $staff = Staff::findOrFail($request->id);
        if ($staff->image != null && file_exists($staff->image))
        {
            unlink($staff->image);
        }
        User::destroy($staff->user_id);
        $staff->delete();
        return back()->with('success', 'Staff deleted successfully.');
    }

    public function changeStatus($id)
    {
        $staff = Staff::findOrFail($id);
        $status = 1;
        if($staff->status == 1){
            $status = 0;
        }
        $staff->update([
            'status' => $status,
        ]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}

