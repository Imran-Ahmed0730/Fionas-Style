<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $data['wishlist'] = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('frontend.customer.wishlist', $data);
    }

    public function store(Request $request)
    {
        $productId = $request->product_id;

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ]);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist',
            'count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist');
    }
}
