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
        $data = $this->cartService->getCartData();
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
        $result = $this->cartService->removeFromCart($request->sku);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']]);
        }

        return response()->json($result);
    }


}
