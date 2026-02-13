<?php

namespace App\Services\Admin;

use App\Models\Admin\AccountLedger;
use App\Models\Admin\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AccountLedgerService
{
    public function getBalanceSheet($startDate = null, $endDate = null)
    {
        $query = AccountLedger::with('accountHead');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } else {
            // Default to current month
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        return $query->latest()->get();
    }

    public function getCashbook($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        return AccountLedger::with('accountHead')
            ->whereDate('created_at', $date)
            ->latest()
            ->get();
    }

    public function getSalesReport($startDate = null, $endDate = null)
    {
        // Get order items with buying prices to calculate profit/loss
        $query = Order::with(['items.stock', 'items.product'])
            ->where('payment_status', 1) // Only paid orders
            ->where('status', '!=', 5); // Exclude cancelled orders

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        return $query->latest()->get();
    }

    public function recordTransaction($data)
    {
        $lastLedger = AccountLedger::latest()->first();
        $previousBalance = $lastLedger ? $lastLedger->balance : 0;

        if ($data['type'] == 1) { // Income
            $data['credit'] = $data['amount'];
            $data['debit'] = 0;
            $data['balance'] = $previousBalance + $data['amount'];
        } else { // Expense
            $data['debit'] = $data['amount'];
            $data['credit'] = 0;
            $data['balance'] = $previousBalance - $data['amount'];
        }

        $data['added_by'] = Auth::id();
        unset($data['amount']);

        return AccountLedger::create($data);
    }

    public function getAllLedgers($request)
    {
        $query = AccountLedger::with('accountHead')->latest();

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('account_head_id') && $request->account_head_id != '') {
            $query->where('account_head_id', $request->account_head_id);
        }

        return $query->paginate(20);
    }
}
