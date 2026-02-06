@extends('backend.master')

@section('title')
    Order Details - {{ $item->invoice_no }}
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Order Items</h4>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.order.invoice', $item->id) }}" class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                    <i class="fa fa-file-invoice"></i> Invoice
                                </a>
                                <a href="{{ route('admin.order.invoice.download', $item->id) }}" class="btn btn-sm btn-outline-success me-2">
                                    <i class="fa fa-download"></i>
                                </a>
                                <a href="{{ route('admin.order.invoice.print', $item->id) }}" class="btn btn-sm btn-outline-secondary me-3" target="_blank">
                                    <i class="fa fa-print"></i>
                                </a>
                                <span class="badge bg-info">{{ $item->invoice_no }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->items as $detail)
                                            <tr>
                                                <td>
                                                    {{ $detail->product->name ?? 'N/A' }}<br>
                                                    <small>{{ $detail->variant_name }}</small>
                                                </td>
                                                <td>{{ getCurrency()['symbol'] }}{{ number_format($detail->price, 2) }}</td>
                                                <td>{{ $detail->quantity }}</td>
                                                <td>{{ getCurrency()['symbol'] }}{{ number_format($detail->price * $detail->quantity, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Subtotal</th>
                                            <td>{{ getCurrency()['symbol'] }}{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Tax</th>
                                            <td>{{ getCurrency()['symbol'] }}{{ number_format($item->tax, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Shipping</th>
                                            <td>{{ getCurrency()['symbol'] }}{{ number_format($item->shipping_cost, 2) }}
                                            </td>
                                        </tr>
                                        @if($item->discount > 0)
                                            <tr>
                                                <th colspan="3" class="text-end">Discount</th>
                                                <td>-{{ getCurrency()['symbol'] }}{{ number_format($item->discount, 2) }}</td>
                                            </tr>
                                        @endif
                                        @if($item->coupon_discount > 0)
                                            <tr>
                                                <th colspan="3" class="text-end">Coupon Discount</th>
                                                <td>-{{ getCurrency()['symbol'] }}{{ number_format($item->coupon_discount, 2) }}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr class="table-primary">
                                            <th colspan="3" class="text-end font-weight-bold">Grand Total</th>
                                            <td class="font-weight-bold">
                                                {{ getCurrency()['symbol'] }}{{ number_format($item->grand_total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Payments Histories</h4>
                            @if(auth()->user()->can($item->type == 1 ? 'Order Online Payment Add' : 'Order POS Payment Add'))
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                    <i class="fa fa-plus"></i> Add Payment
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Trx ID</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->orderPayments as $payment)
                                            <tr>
                                                <td>{{ $payment->created_at->format('d-m-Y H:i') }}</td>
                                                <td>{{ $payment->paymentMethod->name ?? 'N/A' }}</td>
                                                <td>{{ getCurrency()['symbol'] }}{{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ $payment->transaction_id }}</td>
                                                <td>{{ $payment->comment }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No payment found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-info">
                                            <th colspan="2" class="text-end">Total Paid</th>
                                            <td colspan="3">
                                                {{ getCurrency()['symbol'] }}{{ number_format($item->orderPayments->sum('amount'), 2) }}
                                            </td>
                                        </tr>
                                        <tr class="table-warning">
                                            <th colspan="2" class="text-end">Remaining</th>
                                            <td colspan="3">
                                                {{ getCurrency()['symbol'] }}{{ number_format($item->grand_total - $item->orderPayments->sum('amount'), 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order Status</h4>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->can($item->type == 1 ? 'Order Online Update' : 'Order POS Update'))
                                <form action="{{ route('admin.order.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Order Status</label>
                                        <select name="status" class="form-control">
                                            <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Confirmed</option>
                                            <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Processing</option>
                                            <option value="3" {{ $item->status == 3 ? 'selected' : '' }}>Shipped</option>
                                            <option value="4" {{ $item->status == 4 ? 'selected' : '' }}>Delivered</option>
                                            <option value="5" {{ $item->status == 5 ? 'selected' : '' }}>Cancelled</option>
                                            <option value="6" {{ $item->status == 6 ? 'selected' : '' }}>Hold</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Status</label>
                                        <select name="payment_status" class="form-control">
                                            <option value="0" {{ $item->payment_status == 0 ? 'selected' : '' }}>Unpaid</option>
                                            <option value="1" {{ $item->payment_status == 1 ? 'selected' : '' }}>Paid</option>
                                            <option value="2" {{ $item->payment_status == 2 ? 'selected' : '' }}>Partial</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Admin Note</label>
                                        <textarea name="note" class="form-control" rows="3">{{ $item->note }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Update Order</button>
                                </form>
                            @else
                                <div class="alert alert-warning"> You do not have permission to update this order. </div>
                                <div class="mb-3">
                                    <label class="form-label">Order Status</label>
                                    @php
    $statuses = [0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3 => 'Shipped', 4 => 'Delivered', 5 => 'Cancelled', 6 => 'Hold'];
    $pStatuses = [0 => 'Unpaid', 1 => 'Paid', 2 => 'Partial', 3 => 'Refunded'];
                                    @endphp
                                    <p class="form-control-static"><span
                                            class="badge bg-primary">{{ $statuses[$item->status] ?? 'Unknown' }}</span></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Status</label>
                                    <p class="form-control-static"><span
                                            class="badge bg-info">{{ $pStatuses[$item->payment_status] ?? 'Unknown' }}</span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Admin Note</label>
                                    <p class="form-control-static">{{ $item->note ?? 'N/A' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Customer Info</h4>
                            @if(auth()->user()->can($item->type == 1 ? 'Order Online Update' : 'Order POS Update'))
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCustomerModal">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $item->name }}</p>
                            <p><strong>Email:</strong> {{ $item->email }}</p>
                            <p><strong>Phone:</strong> {{ $item->phone }}</p>
                            <p><strong>Address:</strong><br>
                                {{ $item->address }}, {{ $item->city->name ?? '' }}, {{ $item->state->name ?? '' }},
                                {{ $item->country->name ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editCustomerModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('admin.order.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $item->status }}">
                            <input type="hidden" name="payment_status" value="{{ $item->payment_status }}">

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Customer Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $item->email }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $item->phone }}" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="2"
                                            required>{{ $item->address }}</textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Country</label>
                                        <select name="country_id" id="edit_country_id" class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ $item->country_id == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">State</label>
                                        <select name="state_id" id="edit_state_id" class="form-control">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ $item->state_id == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">City</label>
                                        <select name="city_id" id="edit_city_id" class="form-control">
                                            <option value="">Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ $item->city_id == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Info</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.order.add-payment', $item->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method_id" class="form-control" required>
                                @foreach($payment_methods as $pm)
                                    <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                value="{{ $item->grand_total - $item->orderPayments->sum('amount') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transaction ID</label>
                            <input type="text" name="transaction_id" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('#edit_country_id').change(function () {
                var country_id = $(this).val();
                $.ajax({
                    url: "{{ route('checkout.get.states') }}",
                    type: "GET",
                    data: { country_id: country_id },
                    success: function (response) {
                        var options = '<option value="">Select State</option>';
                        $.each(response, function (key, value) {
                            options += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#edit_state_id').html(options);
                        $('#edit_city_id').html('<option value="">Select City</option>');
                    }
                });
            });

            $('#edit_state_id').change(function () {
                var state_id = $(this).val();
                $.ajax({
                    url: "{{ route('checkout.get.cities') }}",
                    type: "GET",
                    data: { state_id: state_id },
                    success: function (response) {
                        var options = '<option value="">Select City</option>';
                        $.each(response, function (key, value) {
                            options += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#edit_city_id').html(options);
                    }
                });
            });
        });
    </script>
@endpush
