@extends('frontend.customer.layout')

@section('customer_content')
    <div class="dashboard-overview">
        <h4 class="mb-4">Dashboard Overview</h4>

        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3">
                    <div class="card-body">
                        <i class="ti-shopping-cart text-warning" style="font-size: 40px;"></i>
                        <h2 class="mt-2">{{ $total_orders }}</h2>
                        <h6 class="text-muted">Total Orders</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3">
                    <div class="card-body">
                        <i class="ti-timer text-info" style="font-size: 40px;"></i>
                        <h2 class="mt-2">{{ $pending_orders }}</h2>
                        <h6 class="text-muted">Pending Orders</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3">
                    <div class="card-body">
                        <i class="ti-heart text-danger" style="font-size: 40px;"></i>
                        <h2 class="mt-2">{{ $wishlist_count }}</h2>
                        <h6 class="text-muted">In Wishlist</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="recent-orders-section mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Recent Orders</h5>
                <a href="{{ route('customer.orders') }}" class="primary-btn py-2 px-3 small"
                    style="background: #e7ab3c;">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_orders as $order)
                            <tr>
                                <td>#{{ $order->invoice_no }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>৳{{ number_format($order->grand_total, 2) }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'secondary';
                                        if ($order->status == 'pending')
                                            $badgeClass = 'warning';
                                        elseif ($order->status == 'confirmed')
                                            $badgeClass = 'info';
                                        elseif ($order->status == 'delivered')
                                            $badgeClass = 'success';
                                        elseif ($order->status == 'cancelled')
                                            $badgeClass = 'danger';
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('customer.order.details', $order->invoice_no) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No recent orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection