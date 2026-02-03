<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $data['items'] = PaymentMethod::latest()->get();
        return view('backend.payment-method.index', $data);
    }

    public function create()
    {
        return view('backend.payment-method.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:payment_methods,name',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $icon = null;
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('payment-methods/icons', 'public');
        }

        PaymentMethod::create([
            'name' => $request->name,
            'icon' => $icon,
            'sandbox' => $request->sandbox ?? 0,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.payment-method.index')->with('success', 'Payment method added successfully');
    }

    public function edit($id)
    {
        $data['item'] = PaymentMethod::findOrFail($id);
        return view('backend.payment-method.form', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:payment_methods,name,' . $id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);

        $icon = $paymentMethod->icon;
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('payment-methods/icons', 'public');
        }

        $paymentMethod->update([
            'name' => $request->name,
            'icon' => $icon,
            'sandbox' => $request->sandbox ?? 0,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.payment-method.index')->with('success', 'Payment method updated successfully');
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
        return back()->with('success', 'Payment method deleted successfully');
    }

    public function changeStatus($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update([
            'status' => !$paymentMethod->status
        ]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
