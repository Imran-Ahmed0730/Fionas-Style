<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function dashboard()
    {
        $data = $this->customerService->getDashboardData();
        return view('frontend.customer.dashboard', $data);
    }

    public function orders()
    {
        $data['orders'] = $this->customerService->getOrders();
        return view('frontend.customer.orders', $data);
    }

    public function orderDetails($invoice)
    {
        $data['order'] = $this->customerService->getOrderDetails($invoice);
        return view('frontend.customer.order_details', $data);
    }

    public function profile()
    {
        $data['user'] = Auth::user();
        $data['customer'] = $data['user']->customer;
        $data['countries'] = \App\Models\Admin\Country::active()->get();
        return view('frontend.customer.profile', $data);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:customers,username,' . Auth::user()->customer->id,
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'country_id' => 'nullable|integer',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'password' => 'nullable|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $this->customerService->updateProfile($request->all());

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function getStates($countryId)
    {
        $states = \App\Models\Admin\State::where('country_id', $countryId)->get();
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = \App\Models\Admin\City::where('state_id', $stateId)->get();
        return response()->json($cities);
    }

    public function destroy()
    {
        $this->customerService->deleteAccount();
        return redirect()->route('home')->with('success', 'Your account has been deleted.');
    }
}
