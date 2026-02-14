<?php

namespace App\Services\Admin;

use App\Models\Admin\Category;
use App\Models\Admin\Brand;
use App\Models\Admin\Product;
use App\Models\Admin\Customer;
use App\Models\Admin\Order;
use App\Models\Admin\OrderItem;
use App\Models\Admin\OrderPayment;
use App\Models\Admin\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Darryldecode\Cart\Facades\CartFacade;
use Darryldecode\Cart\CartCondition;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\State;
use App\Models\Admin\ProductStock;

class PosService
{
    public function getPosData()
    {
        return [
            'categories' => Category::active()->get(),
            'brands' => Brand::active()->get(),
            'payment_methods' => \App\Models\Admin\PaymentMethod::active()->get(),
            'countries' => \App\Models\Admin\Country::active()->get(),
        ];
    }

    public function getProducts($request)
    {
        $query = Product::active()->with(['variants', 'category', 'brand']);

        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id != 'all') {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        return $query->latest()->paginate(12);
    }

    public function getCustomers($search = '')
    {
        $query = Customer::with('user');
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('phone', 'like', "%$search%")
                ->orWhere('username', 'like', "%$search%");
        }
        return $query->limit(10)->get();
    }

    public function storeCustomer($data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'password' => Hash::make($data['phone']),
                'role' => 3, // Customer
            ]);

            return Customer::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'username' => $data['username'] ?? 'user_' . Str::random(5),
                'address' => $data['address'] ?? null,
                'status' => 1,
            ]);
        });
    }

    public function placeOrder($data)
    {
        return DB::transaction(function () use ($data) {
            // Use Darryldecode Cart for processing (Optional but requested)
            // We'll create a unique session ID for this transaction
            $cartSessionId = 'pos_' . Auth::id() . '_' . time();
            CartFacade::session($cartSessionId)->clear();

            foreach ($data['items'] as $item) {
                CartFacade::session($cartSessionId)->add([
                    'id' => $item['id'] . '-' . ($item['variant_id'] ?? '0'),
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'attributes' => [
                        'variant_id' => $item['variant_id'] ?? null,
                    ]
                ]);
            }

            // Apply Conditions (Discount/Shipping)
            if ($data['discount'] > 0) {
                $condition = new CartCondition([
                    'name' => 'POS Discount',
                    'type' => 'discount',
                    'target' => 'subtotal',
                    'value' => '-' . $data['discount'],
                ]);
                CartFacade::session($cartSessionId)->condition($condition);
            }

            if (($data['shipping_cost'] ?? 0) > 0) {
                $condition = new CartCondition([
                    'name' => 'Shipping',
                    'type' => 'shipping',
                    'target' => 'total',
                    'value' => '+' . $data['shipping_cost'],
                ]);
                CartFacade::session($cartSessionId)->condition($condition);
            }

            if (($data['tax'] ?? 0) > 0) {
                $condition = new CartCondition([
                    'name' => 'Tax',
                    'type' => 'tax',
                    'target' => 'total',
                    'value' => '+' . $data['tax'],
                ]);
                CartFacade::session($cartSessionId)->condition($condition);
            }



            $order = Order::create([
                'type' => 2, // POS
                'invoice_no' => (isset($data['is_hold']) && $data['is_hold']) ? null : $this->generateInvoiceNo(),
                'customer_id' => $data['customer_id'] ?? null,
                'name' => $data['customer_name'] ?? 'Walk-in Customer',
                'phone' => $data['customer_phone'] ?? '1234567890',
                'email' => $data['customer_email'] ?? null,
                'subtotal' => CartFacade::session($cartSessionId)->getSubTotal(),
                'tax' => $data['tax'] ?? 0,
                'shipping_cost' => $data['shipping_cost'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'coupon_id' => $data['coupon_id'] ?? null,
                'coupon_discount' => $data['coupon_discount'] ?? 0,
                'grand_total' => ceil(CartFacade::session($cartSessionId)->getTotal()),
                'payment_status' => isset($data['is_hold']) && $data['is_hold'] ? '0' : '1', // 0: Unpaid, 1: Paid
                'status' => isset($data['is_hold']) && $data['is_hold'] ? 6 : 4, // 6: Hold, 4: Delivered
                'created_by' => Auth::id(),
                'country_id' => $data['shipping_info']['country_id'] ?? null,
                'state_id' => $data['shipping_info']['state_id'] ?? null,
                'city_id' => $data['shipping_info']['city_id'] ?? null,
                'address' => $data['shipping_info']['address'] ?? $data['customer_address'] ?? null,
            ]);

            // dd($order);

            foreach (CartFacade::session($cartSessionId)->getContent() as $item) {
                $pId = explode('-', $item->id)[0];
                $product = Product::find($pId);
                if (!$product)
                    continue;

                $variant = $item->attributes->variant_id ? ProductVariant::find($item->attributes->variant_id) : null;

                $stockUsed = null;
                if (!(isset($data['is_hold']) && $data['is_hold'])) {
                    $stockUsed = $this->deductStock($product->id, $variant?->id, $item->quantity);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'product_name' => $product->name ?? $item->name,
                    'sku' => $product->sku ?? 'N/A',
                    'stock_sku' => $stockUsed['stock_sku'] ?? 'N/A',
                    'variant_name' => $variant?->variant_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->getPriceSumWithConditions(),
                    'tax' => $item->attributes->tax ?? 0,
                ]);
            }

            if (isset($data['payments'])) {
                foreach ($data['payments'] as $payment) {
                    OrderPayment::create([
                        'order_id' => $order->id,
                        'payment_method' => $payment['method_id'],
                        'amount' => $payment['amount'],
                        'transaction_id' => $payment['transaction_id'] ?? null,
                    ]);
                }
            }

            CartFacade::session($cartSessionId)->clear();
            CartFacade::session('pos')->clear();
            return $order;
        });
    }

    private function deductStock($productId, $variantId, $quantityToDeduct)
    {
        $query = ProductStock::where('product_id', $productId);

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            $query->where('variant_sku', $variant->sku);
        } else {
            $query->where(function ($q) {
                $q->whereNull('variant_sku')->orWhere('variant_sku', '');
            });
        }

        $stocks = $query->where('qty', '>', 0)->orderBy('added_on', 'asc')->get();
        $remainingToDeduct = $quantityToDeduct;
        $firstStockUsed = null;

        foreach ($stocks as $stock) {
            if ($remainingToDeduct <= 0)
                break;
            if (!$firstStockUsed)
                $firstStockUsed = ['stock_sku' => $stock->sku];

            if ($stock->qty >= $remainingToDeduct) {
                $stock->decrement('qty', $remainingToDeduct);
                $remainingToDeduct = 0;
            } else {
                $remainingToDeduct -= $stock->qty;
                $stock->update(['qty' => 0]);
            }
        }

        return $firstStockUsed ?? [];
    }

    private function generateInvoiceNo()
    {

        return 'FS-' . date('YmdHis') . rand(100, 999999);
    }

    public function updateCustomer($data)
    {
        return DB::transaction(function () use ($data) {
            $customer = Customer::findOrFail($data['id']);
            $customer->update([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? $customer->email,
                'address' => $data['address'] ?? $customer->address,
            ]);

            if ($customer->user_id) {
                User::where('id', $customer->user_id)->update([
                    'name' => $data['name'],
                    'email' => $data['email'] ?? $customer->email,
                ]);
            }

            return $customer;
        });
    }

    public function applyCoupon($code)
    {
        return Coupon::active()
            ->where('code', $code)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('applicable_for', '0')
            ->first();
    }

    public function getHoldOrders()
    {
        return Order::where('type', 2)->where('status', 6)->latest()->get(); // 6 = Hold
    }

    public function getRecentOrders()
    {
        return Order::where('type', 2)->where('status', '!=', 6)->latest()->take(10)->get();
    }

    public function editHoldOrder($id)
    {
        return Order::with(['items', 'customer', 'coupon'])->findOrFail($id);
    }

    public function updateHoldOrder($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $order = Order::findOrFail($id);
            $cartSessionId = 'pos_' . Auth::id() . '_' . time();
            CartFacade::session($cartSessionId)->clear();

            foreach ($data['items'] as $item) {
                CartFacade::session($cartSessionId)->add([
                    'id' => $item['id'] . '-' . ($item['variant_id'] ?? '0'),
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'attributes' => [
                        'variant_id' => $item['variant_id'] ?? null,
                    ]
                ]);
            }

            // Apply Conditions (Discount/Shipping/Tax)
            if (($data['discount'] ?? 0) > 0) {
                $condition = new CartCondition([
                    'name' => 'POS Discount',
                    'type' => 'discount',
                    'target' => 'subtotal',
                    'value' => '-' . $data['discount'],
                ]);
                CartFacade::session($cartSessionId)->condition($condition);
            }

            if (($data['shipping_cost'] ?? 0) > 0) {
                $condition = new CartCondition([
                    'name' => 'Shipping',
                    'type' => 'shipping',
                    'target' => 'total',
                    'value' => '+' . $data['shipping_cost'],
                ]);
                CartFacade::session($cartSessionId)->condition($condition);
            }

            if (($data['tax'] ?? 0) > 0) {
                $condition = new CartCondition([
                    'name' => 'Tax',
                    'type' => 'tax',
                    'target' => 'total',
                    'value' => '+' . $data['tax'],
                ]);
                CartFacade::session($cartSessionId)->condition($condition);
            }

            $order->update([
                'invoice_no' => (isset($data['is_hold']) && $data['is_hold']) ? $order->invoice_no : ($order->invoice_no ?? $this->generateInvoiceNo()),
                'customer_id' => $data['customer_id'] ?? $order->customer_id,
                'name' => $data['customer_name'] ?? $order->name,
                'phone' => $data['customer_phone'] ?? $order->phone,
                'email' => $data['customer_email'] ?? $order->email,
                'subtotal' => CartFacade::session($cartSessionId)->getSubTotal(),
                'tax' => $data['tax'] ?? 0,
                'shipping_cost' => $data['shipping_cost'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'coupon_id' => $data['coupon_id'] ?? $order->coupon_id,
                'coupon_discount' => $data['coupon_discount'] ?? $order->coupon_discount,
                'grand_total' => ceil(CartFacade::session($cartSessionId)->getTotal()),
                'payment_status' => isset($data['is_hold']) && $data['is_hold'] ? '0' : '1',
                'status' => isset($data['is_hold']) && $data['is_hold'] ? 6 : 4,
                'country_id' => $data['shipping_info']['country_id'] ?? $order->country_id,
                'state_id' => $data['shipping_info']['state_id'] ?? $order->state_id,
                'city_id' => $data['shipping_info']['city_id'] ?? $order->city_id,
                'address' => $data['shipping_info']['address'] ?? $data['customer_address'] ?? $order->address,
            ]);

            // Clear old items and add new ones
            $order->items()->delete();
            foreach (CartFacade::session($cartSessionId)->getContent() as $item) {
                $pId = explode('-', $item->id)[0];
                $product = Product::find($pId);
                if (!$product)
                    continue;

                $variant = $item->attributes->variant_id ? ProductVariant::find($item->attributes->variant_id) : null;
                $stockUsed = null;
                if (!(isset($data['is_hold']) && $data['is_hold'])) {
                    $stockUsed = $this->deductStock($product->id, $variant?->id, $item->quantity);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'product_name' => $product->name ?? $item->name,
                    'sku' => $product->sku ?? 'N/A',
                    'stock_sku' => $stockUsed['stock_sku'] ?? 'N/A',
                    'variant_name' => $variant?->variant_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->getPriceSumWithConditions(),
                    'tax' => $item->attributes->tax ?? 0,
                ]);
            }

            if (isset($data['payments'])) {
                $order->orderPayments()->delete();
                foreach ($data['payments'] as $payment) {
                    OrderPayment::create([
                        'order_id' => $order->id,
                        'payment_method' => $payment['method_id'],
                        'amount' => $payment['amount'],
                        'transaction_id' => $payment['transaction_id'] ?? null,
                    ]);
                }
            }

            CartFacade::session($cartSessionId)->clear();
            CartFacade::session('pos')->clear();
            return $order;
        });
    }

    public function deleteHoldOrder($id)
    {
        $order = Order::where('status', 6)->findOrFail($id);
        $order->items()->delete();
        $order->orderPayments()->delete();
        $order->delete();
        return true;
    }

    public function batchAddToCart($data)
    {
        $cart = CartFacade::session('pos');
        $cart->clear();

        foreach ($data['items'] as $item) {
            $product = Product::with('variants')->find($item['product_id']);
            if (!$product)
                continue;

            $variant = null;
            $price = $product->final_price;
            $name = $product->name;
            $image = $product->thumbnail ? asset($product->thumbnail) : asset('backend/assets/img/no-image.png');
            $stock = (int) $product->stock_qty;

            if (isset($item['variant_id']) && $item['variant_id']) {
                $variant = $product->variants()->find($item['variant_id']);
                if ($variant) {
                    $price = $variant->final_price;
                    $name .= ' - ' . ($variant->name ?? $variant->variant_name);
                    $stock = (int) $variant->stock_qty;
                }
            }

            $itemId = $variant ? 'v' . $variant->id : 'p' . $product->id;
            $tax = $product->tax > 0 && $product->tax_inclusion == 2 ? ($price * (float) $product->tax / 100) * $item['quantity'] : 0;

            $cart->add([
                'id' => $itemId,
                'name' => $name,
                'price' => $price,
                'quantity' => $item['quantity'],
                'attributes' => [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'image' => $image,
                    'stock' => $stock,
                    'tax' => $tax,
                    'added_at' => microtime(true)
                ]
            ]);
        }

        return [
            'success' => true,
            'cart' => $this->getCart()
        ];
    }

    public function addToCart($data)
    {
        $product = Product::with('variants')->findOrFail($data['product_id']);
        $variant = null;
        $price = $product->final_price;
        $name = $product->name;
        $image = $product->thumbnail ? asset($product->thumbnail) : asset('backend/assets/img/no-image.png');
        $stock = (int) $product->stock_qty;

        if (isset($data['variant_id']) && $data['variant_id']) {
            $variant = $product->variants()->findOrFail($data['variant_id']);
            $price = $variant->final_price;
            $name .= ' - ' . ($variant->name ?? $variant->variant_name);
            $stock = (int) $variant->stock_qty;
        }

        $cart = CartFacade::session('pos');
        $itemId = $variant ? 'v' . $variant->id : 'p' . $product->id;

        $existingItem = $cart->get($itemId);
        $totalQty = ($existingItem ? $existingItem->quantity : 0) + $data['quantity'];

        if ($totalQty > $stock) {
            return [
                'success' => false,
                'message' => 'Insufficient stock! Available: ' . $stock
            ];
        }

        $tax = $product->tax > 0 && $product->tax_inclusion == 2 ? ($price * (float) $product->tax / 100) * $data['quantity'] : 0;

        $cart->add([
            'id' => $itemId,
            'name' => $name,
            'price' => $price,
            'quantity' => $data['quantity'],
            'attributes' => [
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'image' => $image,
                'stock' => $stock,
                'tax' => $tax,
                'added_at' => $existingItem ? $existingItem->attributes->added_at : microtime(true)
            ]
        ]);

        return [
            'success' => true,
            'message' => 'Item added to cart',
            'cart' => $this->getCart()
        ];
    }

    public function updateCartItem($rowId, $quantity)
    {
        $cart = CartFacade::session('pos');
        $item = $cart->get($rowId);

        if (!$item) {
            return ['success' => false, 'message' => 'Item not found'];
        }

        $product = Product::findOrFail($item->attributes->product_id);
        $tax = $product->tax > 0 && $product->tax_inclusion == 2 ? ($item->price * (float) $product->tax / 100) * $quantity : 0;

        $stock = (int) $item->attributes->stock;
        if ($quantity > $stock) {
            return [
                'success' => false,
                'message' => 'Insufficient stock! Available: ' . $stock
            ];
        }

        $cart->update($rowId, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
            'attributes' => array_merge($item->attributes->toArray(), [
                'tax' => $tax
            ])
        ]);

        $item = $cart->get($rowId);

        return [
            'success' => true,
            'message' => 'Cart updated',
            'cart' => $this->getCart()
        ];
    }

    public function removeCartItem($rowId)
    {
        CartFacade::session('pos')->remove($rowId);

        return [
            'success' => true,
            'message' => 'Item removed from cart',
            'cart' => $this->getCart()
        ];
    }

    public function clearCart()
    {
        CartFacade::session('pos')->clear();

        return [
            'success' => true,
            'message' => 'Cart cleared',
            'cart' => $this->getCart()
        ];
    }

    public function getCart()
    {
        $cart = CartFacade::session('pos');
        $items = $cart->getContent()->map(function ($item) {
            return [
                'row_id' => $item->id,
                'id' => $item->attributes->product_id,
                'variant_id' => $item->attributes->variant_id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => (int) $item->quantity,
                'subtotal' => (float) $item->getPriceSum(),
                'tax' => (float) ($item->attributes->tax ?? 0),
                'image_url' => $item->attributes->image,
                'stock' => $item->attributes->stock,
                'added_at' => $item->attributes->added_at ?? 0
            ];
        })->sortBy('added_at')->values();

        $totalTax = $items->sum('tax');

        // Calculate auto shipping if needed
        $shipping = 0;
        if (request()->has('state_id') && request()->has('order_type') && request()->order_type === 'delivery') {
            $shipping = $this->calculateShipping(request()->state_id);
        }

        return [
            'success' => true,
            'items' => $items,
            'subtotal' => $cart->getSubTotal(),
            'tax' => $totalTax,
            'total' => ceil($cart->getTotal() + $shipping + $totalTax),
            'count' => $cart->getTotalQuantity(),
            'auto_shipping' => $shipping
        ];
    }

    public function calculateShipping($stateId = null)
    {
        $shippingMethod = getSetting('shipping_method', 'location_wise');
        $totalShipping = 0;
        $cart = CartFacade::session('pos');

        $subtotal = $cart->getSubTotal();
        if ($subtotal == 0)
            return 0;

        $freeDeliveryThreshold = (float) getSetting('free_delivery_threshold', 0);
        if ($freeDeliveryThreshold > 0 && $subtotal >= $freeDeliveryThreshold) {
            return 0;
        }

        if ($shippingMethod == 'product_wise') {
            foreach ($cart->getContent() as $item) {
                $product = Product::find($item->attributes->product_id);
                if ($product && ($product->shipping_cost ?? 0) > 0) {
                    $totalShipping += $product->shipping_cost * $item->quantity;
                }
            }
        } elseif ($shippingMethod == 'flat_rate') {
            $totalShipping = (float) getSetting('flat_rate_shipping_cost', 0);
        } else {
            // Location wise
            if (!$stateId) {
                return (float) getSetting('shipping_cost_inside_dhaka', 60);
            }

            $state = State::find($stateId);
            $insideDhaka = $state && str_contains(strtolower($state->name), 'dhaka');

            if ($insideDhaka) {
                $totalShipping = (float) getSetting('shipping_cost_inside_dhaka', 60);
            } else {
                $totalShipping = (float) getSetting('shipping_cost_outside_dhaka', 120);
            }
        }

        return $totalShipping;
    }
}
