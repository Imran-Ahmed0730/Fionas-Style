<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            if(Auth::user()->role == 1 || Auth::user()->role == 4){
                return redirect()->route('admin.dashboard');
            }
            else{
                abort(404);
            }
        }
        else{
            return view('auth.login');
        }
    }

    public function index()
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 4){
            return view('backend.dashboard.index');
        }
        else{
            return redirect()->route('customer.dashboard');
        }
    }

    public function profileEdit()
    {
        return view('backend.profile.edit');
    }

    public function profileUpdate(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        $user = User::find(Auth::id());
        if ($request->email != $user->email) {
            $request->validate([
                'email' => 'unique:users',
            ]);
        }
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:jpeg,png,jpg',
            ]);
            $image = $request->file('image');
            $imagePath = updateImagePath($image, $user->image, 'user-profile' );

        }
        else{
            $imagePath = $user->image;
        }

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'image'     => $imagePath,
            'phone'     => $request->phone,
            'address'   => $request->address
        ]);
        if(Auth::user()->role == 4){
//            $staff = Staff::where('user_id', Auth::id())->first();
//            $staff->update([
//                'name'      => $request->name,
//                'email'     => $request->email,
//                'image'     => $imagePath,
//                'phone'     => $request->phone,
//                'address'   => $request->address
//            ]);
        }
        return back()->with('success', 'Profile updated successfully');
    }
    public function passwordChange(Request $request)
    {
        $request->validate([
            'current_password' => 'required | min:8',
            'password_confirmation' => 'required',
            'password' => 'required | min:8 | confirmed',
        ]);
        if (password_verify($request->current_password, Auth::user()->password)){
            $user = User::find(Auth::id());
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            Auth::logout();
            return redirect()->route('login')->with('success', 'Your password has been changed. Please login again.');
        }
        else{
//            dd('ok');
            return back()->with('error', 'Current password does not match');
        }


    }

    public function roleAssign()
    {
        $data['roles'] = Role::orderBy('name', 'asc')->get();
        $data['users'] = User::latest()->get();
        return view('backend.role-permission.assign_role_to_user' , $data);
    }

    public function roleAssignSubmit(Request $request)
    {
//        return $request;
        $request->validate([
            'role' => 'required',
            'user_id' => 'required',
        ]);
        $user = User::findOrFail($request->user_id);
        if ($user->getRoleNames() != null){
            $user->syncRoles($request->role);
        }
        else{
            $user->assignRole($request->role);
        }
        return back()->with('success', 'Role assigned successfully');
    }
}
