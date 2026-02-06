@extends('frontend.customer.layout')

@section('customer_content')
    <div class="order-list">
        <h4 class="mb-4">My Orders</h4>

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
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->invoice_no }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>৳{{ number_format($order->grand_total, 2) }}</td>
                            <td>
                                @php
                                    $badgeClass = 'secondary';
                                    if ($order->status == '0'){
                                        $badgeClass = 'secondary';
                                        $statusText = 'Pending';
                                    }
                                    elseif ($order->status == '1'){
                                        $badgeClass = 'primary';
                                        $statusText = 'Confirmed';
                                    }
                                    elseif ($order->status == '2'){
                                        $badgeClass = 'info';
                                        $statusText = 'Processing';
                                    }
                                    elseif ($order->status == '3'){
                                        $badgeClass = 'warning';
                                        $statusText = 'Shipped';
                                    }
                                    elseif ($order->status == '4'){
                                        $badgeClass = 'success';
                                        $statusText = 'Delivered';
                                    }
                                    elseif ($order->status == '5'){
                                        $badgeClass = 'danger';
                                        $statusText = 'Cancelled';
                                    }
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($statusText) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('customer.order.details', $order->invoice_no) }}"
                                    class="btn btn-sm btn-outline-primary">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">You haven't placed any orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
