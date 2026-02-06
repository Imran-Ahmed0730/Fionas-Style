@extends('frontend.master')
@section('title', 'Order Summary')
@push('css')
<style>
    .sm-padding td {
        padding: 5px 0;
    }
    .badge {
        padding: 8px 12px;
        font-weight: 500;
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
        display: inline-block;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }
    .badge-warning { background-color: #ffc107; color: #000; }
    .badge-info { background-color: #17a2b8; color: #fff; }
    .badge-primary { background-color: #007bff; color: #fff; }
    .badge-success { background-color: #28a745; color: #fff; }
    .badge-danger { background-color: #dc3545; color: #fff; }
    .badge-secondary { background-color: #6c757d; color: #fff; }

    @media print {
        .breacrumb-section, header, footer, .order-btn, .text-center.mt-5, #preloder, .quickViewModal {
            display: none !important;
        }
        .checkout-section {
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>
@endpush
@section('content')

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Order Summary</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Order Summary Section Begin -->
    <section class="checkout-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="text-center mb-5">
                                <div class="mb-3">
                                    <i class="fa fa-check-circle text-success" style="font-size: 64px;"></i>
                                </div>
                                <h2 class="fw-bold">Thank You For Your Order!</h2>
                                <p class="text-muted">Your order has been placed successfully. A confirmation email has been sent to {{ $order->email ?? 'your phone' }}.</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h5 class="fw-bold mb-3 border-bottom pb-2">Order Information</h5>
                                    <table class="table table-borderless sm-padding">
                                        <tr>
                                            <td class="text-muted" style="width: 150px;">Invoice No:</td>
                                            <td class="fw-bold text-dark">{{ $order->invoice_no }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Date:</td>
                                            <td>{{ $order->created_at->format('d M, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status:</td>
                                            <td>
                                                @php
                                                    $statusLabels = [
                                                        0 => ['label' => 'Pending', 'class' => 'warning'],
                                                        1 => ['label' => 'Confirmed', 'class' => 'info'],
                                                        2 => ['label' => 'Processing', 'class' => 'primary'],
                                                        3 => ['label' => 'Shipped', 'class' => 'success'],
                                                        4 => ['label' => 'Delivered', 'class' => 'success'],
                                                        5 => ['label' => 'Cancelled', 'class' => 'danger'],
                                                    ];
                                                    $currentStatus = $statusLabels[$order->status] ?? ['label' => 'Unknown', 'class' => 'secondary'];
                                                @endphp
                                                <span class="badge badge-{{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Payment:</td>
                                            <td>
                                                @php
                                                    $paymentStatusLabels = [
                                                        '0' => ['label' => 'Unpaid', 'class' => 'danger'],
                                                        '1' => ['label' => 'Paid', 'class' => 'success'],
                                                        '2' => ['label' => 'Partial', 'class' => 'warning'],
                                                        '3' => ['label' => 'Refunded', 'class' => 'info'],
                                                    ];
                                                    $pStatus = $paymentStatusLabels[$order->payment_status] ?? ['label' => 'Unknown', 'class' => 'secondary'];
                                                @endphp
                                                <span class="badge badge-{{ $pStatus['class'] }}">{{ $pStatus['label'] }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h5 class="fw-bold mb-3 border-bottom pb-2">Shipping Address</h5>
                                    <address class="text-muted mb-0">
                                        <strong class="text-dark">{{ $order->name }}</strong><br>
                                        {{ $order->phone }}<br>
                                        {{ $order->address }}<br>
                                        {{ $order->city->name ?? '' }}, {{ $order->state->name ?? '' }}<br>
                                        {{ $order->country->name ?? '' }}
                                    </address>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5 class="fw-bold mb-3 border-bottom pb-2">Order Items</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Product</th>
                                                <th class="text-center">Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset($item->product->thumbnail ?? 'assets/frontend/img/placeholder.png') }}"
                                                                 alt="{{ $item->product_name }}"
                                                                 style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                                            <div>
                                                                <div class="fw-bold">{{ $item->product_name }}</div>
                                                                @if($item->variant_name)
                                                                    <small class="text-muted">Variant: {{ $item->variant_name }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $currency['symbol'] }}{{ number_format($item->price, 2) }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-right fw-bold">{{ $currency['symbol'] }}{{ number_format($item->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-right border-top-0">Subtotal</td>
                                                <td class="text-right border-top-0">{{ $currency['symbol'] }}{{ number_format($order->subtotal, 2) }}</td>
                                            </tr>
                                            @if($order->tax > 0)
                                            <tr>
                                                <td colspan="3" class="text-right border-top-0">Tax</td>
                                                <td class="text-right border-top-0">{{ $currency['symbol'] }}{{ number_format($order->tax, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="3" class="text-right border-top-0">Shipping</td>
                                                <td class="text-right border-top-0">{{ $currency['symbol'] }}{{ number_format($order->shipping_cost, 2) }}</td>
                                            </tr>
                                            @if($order->discount > 0)
                                            <tr>
                                                <td colspan="3" class="text-right border-top-0 text-danger">Discount</td>
                                                <td class="text-right border-top-0 text-danger">-{{ $currency['symbol'] }}{{ number_format($order->discount, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr class="fw-bold" style="font-size: 1.25rem;">
                                                <td colspan="3" class="text-right border-top">Grand Total</td>
                                                <td class="text-right border-top text-primary">{{ $currency['symbol'] }}{{ number_format($order->grand_total, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="text-center mt-5">
                                <a href="{{ route('home') }}" class="site-btn mr-3">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order Summary Section End -->

@endsection


