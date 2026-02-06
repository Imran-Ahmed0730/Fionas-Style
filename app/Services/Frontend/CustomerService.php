<?php

namespace App\Services\Frontend;

use App\Models\Admin\Order;
use App\Models\Frontend\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    public function getDashboardData()
    {
        $user = Auth::user();
        $customer = $user->customer;

        return [
            'total_orders' => Order::where('customer_id', $customer->id)->count(),
            'pending_orders' => Order::where('customer_id', $customer->id)->where('status', 0)->count(),
            'recent_orders' => Order::where('customer_id', $customer->id)->latest()->limit(5)->get(),
            'wishlist_count' => Wishlist::where('user_id', $user->id)->count(),
        ];
    }

    public function getOrders($perPage = 10)
    {
        $customer = Auth::user();
        $customerId = $customer ? $customer->id : null;

        return Order::where('customer_id', $customerId)
            ->with(['orderPayments'])
            ->latest()
            ->paginate($perPage);
    }

    public function getOrderDetails($invoice)
    {
        $customer = Auth::user();
        $customerId = $customer ? $customer->id : null;

        return Order::where('customer_id', $customerId)
            ->where('invoice_no', $invoice)
            ->with(['items.product', 'orderPayments.paymentMethod', 'country', 'state', 'city'])
            ->firstOrFail();
    }

    public function updateProfile($data)
    {
        $user = Auth::user();
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $customer = $user->customer;
        $customer->update([
            'phone' => $data['phone'],
            'username' => $data['username'],
            'address' => $data['address'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
            'city_id' => $data['city_id'],
        ]);

        if (request()->hasFile('image')) {
            $customer->image = saveImagePath(request()->file('image'), $customer->image, 'customer');
            $customer->save();
        }

        return $user;
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        $customer = $user->customer;

        // Record deletion
        \App\Models\Admin\DeletedCustomer::create([
            'name' => $user->name,
            'phone' => $customer->phone ?? 'N/A',
            'email' => $user->email ?? 'N/A',
        ]);

        // Delete image if exists
        if ($customer && $customer->image && file_exists($customer->image)) {
            unlink($customer->image);
        }

        // Logout first
        Auth::logout();

        // Delete customer and user
        if ($customer) {
            $customer->delete();
        }

        return $user->delete();
    }
}
