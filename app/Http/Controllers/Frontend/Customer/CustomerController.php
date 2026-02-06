<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Customer\ProfileUpdateRequest;
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
        return view('frontend.customer.dashboard.index', $data);
    }

    public function orders()
    {
        $data['orders'] = $this->customerService->getOrders();
        return view('frontend.customer.order.index', $data);
    }

    public function orderDetails($invoice)
    {
        $data['order'] = $this->customerService->getOrderDetails($invoice);
        return view('frontend.customer.order.details', $data);
    }

    public function profile()
    {
        $data['user'] = Auth::user();
        $data['customer'] = $data['user']->customer;
        $data['countries'] = \App\Models\Admin\Country::active()->get();
        return view('frontend.customer.profile.index', $data);
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {

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
