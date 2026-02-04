@extends('frontend.customer.layout')

@section('customer_content')
    <div class="order-details">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Order Details - #{{ $order->invoice_no }}</h4>
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary btn-sm"><i class="ti-arrow-left"></i>
                Back to Orders</a>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white font-weight-bold">Shipping Information</div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> {{ $order->name }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $order->address }},
                            {{ $order->city ? $order->city->name : '' }}, {{ $order->state ? $order->state->name : '' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white font-weight-bold">Order Summary</div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                        <p class="mb-1"><strong>Order Status:</strong>
                            <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                        </p>
                        <p class="mb-1"><strong>Payment Status:</strong>
                            <span class="badge badge-success">{{ ucfirst($order->payment_status) }}</span>
                        </p>
                        <p class="mb-1"><strong>Payment Method:</strong> {{ $order->payment_type }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white font-weight-bold">Items Ordered</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset($item->product->thumbnail) }}" alt=""
                                                    style="width: 50px; margin-right: 10px;">
                                            @endif
                                            <span>{{ $item->product_name ?? ($item->product ? $item->product->name : 'N/A') }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->variant_name ?? '-' }}</td>
                                    <td>৳{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-right">৳{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-right">Subtotal</td>
                                <td class="text-right">৳{{ number_format($order->sub_total, 2) }}</td>
                            </tr>
                            @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="4" class="text-right">Shipping Cost</td>
                                    <td class="text-right">৳{{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                            @endif
                            @if($order->coupon_discount > 0)
                                <tr>
                                    <td colspan="4" class="text-right text-danger">Coupon Discount (-)</td>
                                    <td class="text-right text-danger">৳{{ number_format($order->coupon_discount, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="font-weight-bold" style="font-size: 1.1rem;">
                                <td colspan="4" class="text-right">Grand Total</td>
                                <td class="text-right">৳{{ number_format($order->grand_total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection