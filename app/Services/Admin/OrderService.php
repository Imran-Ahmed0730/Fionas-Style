<?php

namespace App\Services\Admin;

use App\Models\Admin\Order;
use App\Models\Admin\OrderPayment;
use App\Models\Admin\AccountLedger;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderService
{
    public function getOrders($request, $type)
    {
        $orders = Order::with(['customer'])
            ->where('type', $type)
            ->orderBy('id', 'desc');

        if (!$request->filled('date_range')) {
            $orders->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'));
        } else {
            [$startDate, $endDate] = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
            $orders->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('payment_method_id')) {
            $orders->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->filled('payment_status')) {
            $orders->where('payment_status', $request->payment_status);
        }

        if ($request->filled('status')) {
            $orders->where('status', $request->status);
        }

        return $orders->get();
    }

    public function updateOrder($id, $data)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $data['status'];
        $newPaymentStatus = $data['payment_status'];

        // Validate payment status change
        if ($newPaymentStatus == 1) { // Attempting to mark as "Paid"
            $totalPaid = $order->orderPayments()->sum('amount');
            if ($totalPaid < $order->grand_total) {
                throw new \Exception("Cannot mark order as paid. Total paid ({$totalPaid}) is less than order total ({$order->grand_total})");
            }
        }

        $updatePayload = [
            'status' => $newStatus,
            'payment_status' => $newPaymentStatus,
            'note' => $data['note'] ?? null,
        ];

        // Handle optional customer data
        if (isset($data['name']))
            $updatePayload['name'] = $data['name'];
        if (isset($data['email']))
            $updatePayload['email'] = $data['email'];
        if (isset($data['phone']))
            $updatePayload['phone'] = $data['phone'];
        if (isset($data['address']))
            $updatePayload['address'] = $data['address'];
        if (isset($data['country_id']))
            $updatePayload['country_id'] = $data['country_id'];
        if (isset($data['state_id']))
            $updatePayload['state_id'] = $data['state_id'];
        if (isset($data['city_id']))
            $updatePayload['city_id'] = $data['city_id'];

        if ($newStatus == 1 && $oldStatus != 1)
            $updatePayload['confirmed_at'] = now();
        if ($newStatus == 3 && $oldStatus != 3)
            $updatePayload['shipped_at'] = now();
        if ($newStatus == 4 && $oldStatus != 4){
            $updatePayload['delivered_at'] = now();
            $this->updateOrderItemSellCount($order);
        }
        if ($newStatus == 5 && $oldStatus != 5)
            $updatePayload['cancelled_at'] = now();

        $order->update($updatePayload);

        if ($order->status == 4 && $order->payment_status == 1) {
            $this->addToLedger($order);
        }

        return $order;
    }

    public function addPayment($id, $data)
    {
        $order = Order::findOrFail($id);

        OrderPayment::create([
            'order_id' => $order->id,
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount'],
            'account_number' => $data['account_number'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
            'comment' => $data['comment'] ?? null,
        ]);

        // Add payment to ledger immediately
        $this->addPaymentToLedger($order, $data['amount']);

        $totalPaid = $order->orderPayments()->sum('amount');
        if ($totalPaid >= $order->grand_total) {
            $order->update(['payment_status' => 1]); // Paid
            if ($order->status == 4) {
                $this->addToLedger($order);
            }
        } elseif ($totalPaid > 0) {
            $order->update(['payment_status' => 2]); // Partial
        }
    }

    private function addToLedger($order)
    {
        $exists = AccountLedger::where('order_id', $order->id)->exists();
        if ($exists)
            return;

        $prev_balance = AccountLedger::orderBy('id', 'desc')->first();
        $balance = $prev_balance ? $prev_balance->balance : 0;
        $current_balance = $balance + $order->grand_total;

        AccountLedger::create([
            'account_head_id' => 1,
            'type' => 1,
            'particular' => 'Order payment for invoice: ' . $order->invoice_no,
            'credit' => $order->grand_total,
            'debit' => 0,
            'balance' => $current_balance,
            'order_id' => $order->id,
            'added_by' => Auth::id(),
        ]);
    }

    private function addPaymentToLedger($order, $amount)
    {
        $prev_balance = AccountLedger::orderBy('id', 'desc')->first();
        $balance = $prev_balance ? $prev_balance->balance : 0;
        $current_balance = $balance + $amount;

        AccountLedger::create([
            'account_head_id' => 1,
            'type' => 1,
            'particular' => 'Payment received for Order: ' . $order->invoice_no,
            'credit' => $amount,
            'debit' => 0,
            'balance' => $current_balance,
            'order_id' => $order->id,
            'added_by' => Auth::id(),
        ]);
    }

    public function updateOrderItemSellCount(Order $order)
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->increment('sell_count', $item->quantity);
            }
        }
    }
}
