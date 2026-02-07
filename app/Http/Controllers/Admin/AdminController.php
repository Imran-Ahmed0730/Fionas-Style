<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordChangeRequest;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\RoleAssignRequest;
use App\Models\User;
use App\Services\Admin\AdminService;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
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
            $data = $this->adminService->getDashboardData();
            return view('backend.dashboard.index', $data);
        }
        else{
            return redirect()->route('customer.dashboard');
        }
    }

    public function profileEdit()
    {
        return view('backend.profile.index');
    }

    public function profileUpdate(ProfileUpdateRequest $request){
        $user = User::findOrFail(Auth::id());
        $this->adminService->updateProfile($user, $request->validated(), $request->file('image'));
        return back()->with('success', 'Profile updated successfully');
    }
    public function passwordChange(PasswordChangeRequest $request)
    {
        if (password_verify($request->current_password, Auth::user()->password)){
            $user = User::findOrFail(Auth::id());
            $this->adminService->changePassword($user, $request->password);
            Auth::logout();
            return redirect()->route('login')->with('success', 'Your password has been changed. Please login again.');
        }
        else{
            return back()->with('error', 'Current password does not match');
        }
    }

    public function roleAssign()
    {
        $data['roles'] = Role::orderBy('name', 'asc')->get();
        $data['users'] = User::latest()->get();
        return view('backend.role-permission.assign_role_to_user' , $data);
    }

    public function roleAssignSubmit(RoleAssignRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $this->adminService->assignRole($user, $request->role);
        return back()->with('success', 'Role assigned successfully');
    }
}
