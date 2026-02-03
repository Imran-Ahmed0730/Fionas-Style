<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\City;
use App\Models\Admin\Country;
use App\Models\Admin\State;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function onlineOrders(Request $request)
    {
        return $this->index($request, 1, 'Online Orders');
    }

    public function posOrders(Request $request)
    {
        return $this->index($request, 2, 'POS Orders');
    }

    private function index(Request $request, $type, $typeName)
    {
        $orders = $this->orderService->getOrders($request, $type);

        $data['items'] = $orders;
        $data['payment_methods'] = PaymentMethod::where('status', 1)->get();
        $data['order_type'] = $type;
        $data['type_name'] = $typeName;

        return view('backend.order.index', $data);
    }

    public function show($id)
    {
        $data['item'] = Order::with(['items', 'orderPayments.paymentMethod', 'country', 'state', 'city'])->findOrFail($id);
        $data['payment_methods'] = PaymentMethod::where('status', 1)->get();

        $data['countries'] = Country::where('status', 1)->get();
        $data['states'] = $data['item']->country_id ? State::where('country_id', $data['item']->country_id)->get() : collect();
        $data['cities'] = $data['item']->state_id ? City::where('state_id', $data['item']->state_id)->get() : collect();

        return view('backend.order.details', $data);
    }

    public function edit($id)
    {
        $data['item'] = Order::findOrFail($id);
        $data['countries'] = Country::where('status', 1)->get();
        $data['states'] = State::where('country_id', $data['item']->country_id)->get();
        $data['cities'] = City::where('state_id', $data['item']->state_id)->get();
        return view('backend.order.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'payment_status' => 'required',
        ]);

        $this->orderService->updateOrder($id, $request->all());

        return redirect()->back()->with('success', 'Order updated successfully');
    }

    public function addPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method_id' => 'required',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $this->orderService->addPayment($id, $request->all());

        return back()->with('success', 'Payment added successfully');
    }
}
