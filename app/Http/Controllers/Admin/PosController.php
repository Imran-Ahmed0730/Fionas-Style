<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PosService;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Customer;

use App\Http\Requests\Admin\PosOrderRequest;

class PosController extends Controller
{
    protected $posService;

    public function __construct(PosService $posService)
    {
        $this->posService = $posService;
    }

    public function index()
    {
        $data = $this->posService->getPosData();
        return view('backend.pos.index', $data);
    }

    public function getProducts(Request $request)
    {
        $products = $this->posService->getProducts($request);
        return view('backend.pos.partials.product_list', compact('products'))->render();
    }

    public function getCustomers(Request $request)
    {
        $customers = $this->posService->getCustomers($request->search);
        return response()->json($customers);
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:customers,phone',
        ]);

        $customer = $this->posService->storeCustomer($request->all());
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function placeOrder(PosOrderRequest $request)
    {
        try {
            $order = $this->posService->placeOrder($request->validated());
            return redirect()->route('admin.order.invoice', $order->id)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error placing order: ' . $e->getMessage());
        }
    }

    public function holdOrder(PosOrderRequest $request)
    {
        try {
            $data = $request->validated();
            // dd($data);
            $data['is_hold'] = true;
            $this->posService->placeOrder($data);
            return redirect()->route('admin.pos.index')->with('success', 'Order held successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error holding order: ' . $e->getMessage());
        }
    }

    public function editCustomer($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return response()->json($customer);
    }

    public function updateCustomer(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id',
            'name' => 'required',
            'phone' => 'required|unique:customers,phone,' . $request->id,
        ]);

        $customer = $this->posService->updateCustomer($request->all());
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required']);
        $coupon = $this->posService->applyCoupon($request->code);
        if ($coupon) {
            return response()->json(['success' => true, 'coupon' => $coupon]);
        }
        return response()->json(['success' => false, 'message' => 'Invalid or expired coupon']);
    }

    public function getVariants($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return response()->json($product->variants);
    }

    public function getHoldOrders()
    {
        $orders = $this->posService->getHoldOrders();
        return response()->json($orders);
    }

    public function getRecentOrders()
    {
        $orders = $this->posService->getRecentOrders();
        return response()->json($orders);
    }

    public function editHoldOrder($id)
    {
        $order = $this->posService->editHoldOrder($id);
        return response()->json($order);
    }

    public function updateHoldOrder(PosOrderRequest $request, $id)
    {
        try {
            $order = $this->posService->updateHoldOrder($id, $request->validated());
            if (isset($request->is_hold) && $request->is_hold) {
                return redirect()->route('admin.pos.index')->with('success', 'Order held successfully!');
            }
            return redirect()->route('admin.order.invoice', $order->id)->with('success', 'Order processed!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating hold order: ' . $e->getMessage());
        }
    }

    public function deleteHoldOrder($id)
    {
        try {
            $this->posService->deleteHoldOrder($id);
            return response()->json(['success' => true, 'message' => 'Hold order deleted!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getCityShippingCost($cityId)
    {
        $city = \App\Models\Admin\City::find($cityId);
        if ($city && $city->name == 'Dhaka') {
            return response()->json(['success' => true, 'shipping_cost' => getSetting('shipping_cost_inside_dhaka')]);
        }
        return response()->json(['success' => false, 'shipping_cost' => getSetting('shipping_cost_outside_dhaka')]);
    }

    public function batchAddToCart(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_id' => 'nullable'
        ]);

        $result = $this->posService->batchAddToCart($request->all());
        return response()->json($result);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $result = $this->posService->addToCart($request->all());
        return response()->json($result);
    }

    public function updateCartItem(Request $request)
    {
        $request->validate([
            'row_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $result = $this->posService->updateCartItem($request->row_id, $request->quantity);
        return response()->json($result);
    }

    public function removeCartItem($rowId)
    {
        $result = $this->posService->removeCartItem($rowId);
        return response()->json($result);
    }

    public function clearCart()
    {
        $result = $this->posService->clearCart();
        return response()->json($result);
    }

    public function getCart()
    {
        $result = $this->posService->getCart();
        return response()->json($result);
    }
}
