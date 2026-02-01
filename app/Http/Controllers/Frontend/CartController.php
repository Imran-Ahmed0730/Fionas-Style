<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\CartService;
use Cart;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the cart page
     */
    public function index()
    {
        // $cache_duration = config('custom.cache_duration', 60); 
        // Logic from reference: cache translations.
        // For now returning simple view

        $data['items'] = Cart::getContent();
        $data['items'] = Cart::getContent();

        return view('frontend.cart.index', $data);
    }

    /**
     * Add to Cart
     */
    public function cartAdd(Request $request)
    {
        $result = $this->cartService->addToCart($request);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']]);
        }

        return response()->json($result);
    }

    /**
     * Update Cart Quantity
     */
    public function update(Request $request)
    {
        $result = $this->cartService->updateCart($request);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 404); // Or 400
        }

        return response()->json($result);
    }

    /**
     * Remove from Cart
     */
    public function cartRemove(Request $request)
    {
        $success = $this->cartService->removeFromCart($request->sku);

        if ($success) {
            return response()->json(['success' => 'Product removed from cart']);
        } else {
            return response()->json(['error' => 'Product not removed from cart']);
        }
    }

    /**
     * Update Item Checked Status
     */
    public function updateCheckedStatus(Request $request)
    {
        $success = $this->cartService->updateCheckStatus($request->sku, $request->check_status);

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }
}
