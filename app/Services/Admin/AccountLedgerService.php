<?php

namespace App\Services\Admin;

use App\Models\Admin\AccountLedger;
use Carbon\Carbon;

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
        // Logic to calculate balance would go here if tracking running balance
        // For simplicity, we just store debit/credit
        return AccountLedger::create($data);
    }
}
