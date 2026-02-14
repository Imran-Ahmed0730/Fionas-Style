<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Admin\Product;
use App\Models\Admin\Order;
use App\Models\Admin\OrderPayment;
use App\Models\Admin\ProductStock;
use App\Models\Admin\AccountLedger;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AdminService
{
    public function updateProfile(User $user, array $data, ?UploadedFile $image): bool
    {
        $imagePath = $image ? saveImagePath($image, $user->image, 'user-profile') : $user->image;

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'image' => $imagePath,
            'phone' => $data['phone'] ?? $user->phone,
            'address' => $data['address'] ?? $user->address,
        ];

        return $user->update($updateData);
    }

    public function changePassword(User $user, string $newPassword): bool
    {
        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function assignRole(User $user, string $role): void
    {
        if ($user->getRoleNames()->isNotEmpty()) {
            $user->syncRoles($role);
        } else {
            $user->assignRole($role);
        }
    }

    /**
     * Gather dashboard data used by admin dashboard (cached).
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        return Cache::remember('admin.dashboard.data', 300, function () {
            $today = \Carbon\Carbon::now()->startOfDay();
            $thisMonth = \Carbon\Carbon::now()->startOfMonth();

            $totalProductQty = ProductStock::sum('qty');
            $totalStockCost = \Illuminate\Support\Facades\DB::table('product_stocks')
                ->selectRaw('SUM(qty * buying_price) as total_cost')
                ->value('total_cost') ?? 0;

            $todaySales = Order::where('payment_status', 1)
                ->where('status', '!=', 5)
                ->whereDate('created_at', $today)
                ->sum('grand_total');

            $monthSales = Order::where('payment_status', 1)
                ->where('status', '!=', 5)
                ->whereBetween('created_at', [$thisMonth, \Carbon\Carbon::now()])
                ->sum('grand_total');

            // Order graph data (last 7 days)
            $last7Days = collect(range(0, 6))->map(function ($i) {
                return \Carbon\Carbon::now()->subDays($i);
            })->reverse();

            $dates = [];
            $posOrders = [];
            $onlineOrders = [];
            foreach ($last7Days as $date) {
                $dates[] = $date->format('M d');
                $posOrders[] = Order::where('type', 2)->whereDate('created_at', $date)->count();
                $onlineOrders[] = Order::where('type', 1)->whereDate('created_at', $date)->count();
            }

            // Payment chart
            $paymentMethods = OrderPayment::with('paymentMethod')
                ->select('payment_method')
                ->selectRaw('COUNT(*) as count, SUM(amount) as total_amount')
                ->whereHas('order', function ($q) {
                    $q->where('payment_status', 1);
                })
                ->groupBy('payment_method')
                ->get();

            $paymentLabels = [];
            $paymentAmounts = [];
            foreach ($paymentMethods as $method) {
                $paymentLabels[] = $method->paymentMethod->name ?? 'Unknown';
                $paymentAmounts[] = (float) $method->total_amount;
            }

            // Only Today's Transactions
            $todayTransactions = Order::with('orderPayments.paymentMethod')
                ->where('payment_status', '!=', 0)
                ->whereDate('created_at', $today)
                ->latest()
                ->get();

            $newCustomers = User::where('role', 3)
                ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                ->latest()
                ->take(10)
                ->get();

            $topSellingProducts = Product::select('products.id', 'products.name')
                ->selectRaw('SUM(order_items.quantity) as total_sold')
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.payment_status', 1)
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

            $topSellingLabels = $topSellingProducts->pluck('name')->toArray();
            $topSellingCounts = $topSellingProducts->pluck('total_sold')->toArray();

            $todaysDealProducts = Product::where('include_to_todays_deal', 1)
                ->where('status', 1)
                ->take(10)
                ->get();

            $totalOrders = Order::count();
            $totalCustomers = User::where('role', 3)->count();

            $latestLedger = AccountLedger::latest()->first();
            $currentBalance = $latestLedger ? $latestLedger->balance : 0;

            return [
                'totalProductQty' => $totalProductQty,
                'totalStockCost' => $totalStockCost,
                'todaySales' => $todaySales,
                'monthSales' => $monthSales,
                'currentBalance' => $currentBalance,
                'orderGraphData' => [
                    'dates' => json_encode($dates),
                    'posOrders' => json_encode($posOrders),
                    'onlineOrders' => json_encode($onlineOrders),
                ],
                'paymentChartData' => [
                    'labels' => json_encode($paymentLabels),
                    'amounts' => json_encode($paymentAmounts),
                ],
                'todayTransactions' => $todayTransactions,
                'newCustomers' => $newCustomers,
                'topSellingChartData' => [
                    'labels' => json_encode($topSellingLabels),
                    'counts' => json_encode($topSellingCounts),
                ],
                'todaysDealProducts' => $todaysDealProducts,
                'totalOrders' => $totalOrders,
                'totalCustomers' => $totalCustomers,
            ];
        });
    }
}
