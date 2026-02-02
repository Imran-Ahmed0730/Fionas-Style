<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\State;
use App\Models\Admin\City;
use App\Services\Frontend\CartService;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        if (\Cart::getTotalQuantity() == 0) {
            return redirect()->route('shop')->with('warning', 'Your cart is empty');
        }

        $summary = $this->cartService->getCartData();

        $data['items'] = $summary['items'];
        $data['subtotal'] = $summary['subtotal'];
        $data['tax'] = $summary['tax'];
        $data['shipping_cost'] = $summary['shipping_cost'];
        $data['discount'] = $summary['discount'];
        $data['coupon_discount'] = $summary['coupon_discount'];
        $data['coupon_code'] = $summary['coupon_code'];
        $data['grand_total'] = $summary['grand_total'];

        $data['countries'] = Country::active()->orderBy('name', 'asc')->get();
        // Assuming Bangladesh is common, get its states as default or let AJAX handle it
        $bd = Country::where('name', 'Bangladesh')->first();
        $data['states'] = $bd ? State::where('country_id', $bd->id)->orderBy('name', 'asc')->get() : [];

        return view('frontend.checkout.index', $data);
    }

    public function getStates(Request $request)
    {
        $states = State::where('country_id', $request->country_id)->orderBy('name', 'asc')->get();
        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->orderBy('name', 'asc')->get();

        // Persist state in session for shipping calculation
        Session::put('shipping_state_id', $request->state_id);

        $cartData = $this->cartService->getCartData();

        return response()->json([
            'cities' => $cities,
            'summary' => $cartData
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required']);

        $existingCoupon = Session::get('applied_coupon');
        if ($existingCoupon && $existingCoupon == $request->coupon_code) {
            return response()->json(['error' => 'This coupon is already applied'], 422);
        }

        Session::put('applied_coupon', $request->coupon_code);
        $cartData = $this->cartService->getCartData();

        if (isset($cartData['coupon_error']) && $cartData['coupon_error']) {
            Session::forget('applied_coupon');
            return response()->json(['error' => $cartData['coupon_error']], 422);
        }

        return response()->json([
            'success' => 'Coupon applied successfully',
            'summary' => $cartData
        ]);
    }

    public function removeCoupon()
    {
        Session::forget('applied_coupon');
        $cartData = $this->cartService->getCartData();

        return response()->json([
            'success' => 'Coupon removed successfully',
            'summary' => $cartData
        ]);
    }
}
