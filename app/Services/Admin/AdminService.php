<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AdminService
{
    public function updateProfile(User $user, array $data, ?UploadedFile $image): bool
    {
        $imagePath = $image ? saveImagePath($image, $user->image, 'user-profile') : $user->image;

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'image'     => $imagePath,
            'phone'     => $data['phone'] ?? $user->phone,
            'address'   => $data['address'] ?? $user->address,
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
        if ($user->getRoleNames()->isNotEmpty()){
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

            $totalProductQty = \App\Models\Admin\ProductStock::sum('qty');
            $totalStockCost = \Illuminate\Support\Facades\DB::table('product_stocks')
                ->selectRaw('SUM(qty * buying_price) as total_cost')
                ->value('total_cost') ?? 0;

            $todaySales = \App\Models\Admin\Order::where('payment_status', 1)
                ->where('status', '!=', 5)
                ->whereDate('created_at', $today)
                ->sum('grand_total');

            $monthSales = \App\Models\Admin\Order::where('payment_status', 1)
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
                $posOrders[] = \App\Models\Admin\Order::where('type', 2)->whereDate('created_at', $date)->count();
                $onlineOrders[] = \App\Models\Admin\Order::where('type', 1)->whereDate('created_at', $date)->count();
            }

            // Payment chart
            $paymentMethods = \App\Models\Admin\OrderPayment::with('paymentMethod')
                ->select('payment_method')
                ->selectRaw('COUNT(*) as count, SUM(amount) as total_amount')
                ->whereHas('order', function ($q) { $q->where('payment_status', 1); })
                ->groupBy('payment_method')
                ->get();

            $labels = [];
            $amounts = [];
            foreach ($paymentMethods as $method) {
                $labels[] = $method->paymentMethod->name ?? 'Unknown';
                $amounts[] = round($method->total_amount, 2);
            }

            $recentTransactions = \App\Models\Admin\Order::with('orderPayments.paymentMethod')
                ->where('payment_status', '!=', 0)
                ->latest()
                ->take(10)
                ->get();

            $recentOrders = \App\Models\Admin\Order::with('customer')
                ->latest()
                ->take(8)
                ->get();

            $newCustomers = \App\Models\User::where('role', 3)
                ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                ->latest()
                ->take(10)
                ->get();

            $mostViewedProducts = \App\Models\Admin\Product::orderBy('created_at', 'desc')->take(6)->get();

            $mostSoldProducts = \App\Models\Admin\Product::with('stocks')
                ->select('products.*')
                ->selectRaw('SUM(order_items.quantity) as total_sold')
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.payment_status', 1)
                ->where('orders.created_at', '>=', \Carbon\Carbon::now()->subDays(30))
                ->groupBy('products.id')
                ->orderByDesc('total_sold')
                ->take(6)
                ->get();

            $totalOrders = \App\Models\Admin\Order::count();
            $pendingOrders = \App\Models\Admin\Order::where('status', 0)->count();
            $totalCustomers = \App\Models\User::where('role', 3)->count();

            return [
                'totalProductQty' => $totalProductQty,
                'totalStockCost' => $totalStockCost,
                'todaySales' => $todaySales,
                'monthSales' => $monthSales,
                'orderGraphData' => [
                    'dates' => json_encode($dates),
                    'posOrders' => json_encode($posOrders),
                    'onlineOrders' => json_encode($onlineOrders),
                ],
                'paymentChartData' => [
                    'labels' => json_encode($labels),
                    'amounts' => json_encode($amounts),
                    'colors' => json_encode(['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']),
                ],
                'recentTransactions' => $recentTransactions,
                'recentOrders' => $recentOrders,
                'newCustomers' => $newCustomers,
                'mostViewedProducts' => $mostViewedProducts,
                'mostSoldProducts' => $mostSoldProducts,
                'totalOrders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
                'totalCustomers' => $totalCustomers,
            ];
        });
    }
}
