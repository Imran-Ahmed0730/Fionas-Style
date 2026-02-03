<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\State;
use App\Models\Admin\City;
use App\Models\Admin\Order;
use App\Services\Frontend\CartService;
use App\Services\Frontend\OrderService;
use App\Http\Requests\Frontend\OrderRequest;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
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

        // Handle pre-selected country/state/city from old input or auth guest/customer
        $selectedCountry = old('country_id') ?? (auth()->user() && auth()->user()->customer ? auth()->user()->customer->country_id : null);
        $selectedState = old('state_id') ?? (auth()->user() && auth()->user()->customer ? auth()->user()->customer->state_id : null);

        if ($selectedCountry) {
            $data['states'] = State::where('country_id', $selectedCountry)->orderBy('name', 'asc')->get();
        } else {
            // Default to Bangladesh if no selection exists
            $bd = Country::where('name', 'Bangladesh')->first();
            $data['states'] = $bd ? State::where('country_id', $bd->id)->orderBy('name', 'asc')->get() : [];
        }

        $data['cities'] = $selectedState ? City::where('state_id', $selectedState)->orderBy('name', 'asc')->get() : [];

        // Add currency for the view
        $data['currency'] = getCurrency();

        return view('frontend.checkout.index', $data);
    }

    /**
     * Handle order placement
     */
    public function placeOrder(OrderRequest $request)
    {
        try {
            if (\Cart::getTotalQuantity() == 0) {
                return redirect()->route('shop')->with('warning', 'Your cart is empty');
            }

            $cartSummary = $this->cartService->getCartData();
            $order = $this->orderService->placeOrder($request->validated(), $cartSummary);

            return redirect()->route('checkout.summary', $order->invoice_no)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            Log::error('Order Placement Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show order summary page
     */
    public function orderSummary($invoice)
    {
        $order = Order::with(['items.product', 'items.variant', 'country', 'state', 'city'])->where('invoice_no', $invoice)->firstOrFail();
        $data['order'] = $order;
        $data['currency'] = getCurrency();
        return view('frontend.checkout.summary', $data);
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