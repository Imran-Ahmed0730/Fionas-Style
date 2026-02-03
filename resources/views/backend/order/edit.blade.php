@extends('backend.master')

@section('title')
    Edit Order - {{ $item->invoice_no }}
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <form action="{{ route('admin.order.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Customer Information</h4>
                            </div>
                            <div class="card-body">
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
                                        <textarea name="address" class="form-control" rows="2" required>{{ $item->address }}</textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Country</label>
                                        <select name="country_id" id="country_id" class="form-control">
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
                                        <select name="state_id" id="state_id" class="form-control">
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
                                        <select name="city_id" id="city_id" class="form-control">
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
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h4 class="card-title">Order Items (Read Only)</h4>
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
                                                    <td>{{ $detail->qty }}</td>
                                                    <td>{{ getCurrency()['symbol'] }}{{ number_format($detail->price * $detail->qty, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Grand Total</th>
                                                <td>{{ getCurrency()['symbol'] }}{{ number_format($item->grand_total, 2) }}</td>
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
                                <h4 class="card-title">Order Settings</h4>
                            </div>
                            <div class="card-body">
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
                                <a href="{{ route('admin.order.show', $item->id) }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#country_id').change(function() {
                var country_id = $(this).val();
                $.ajax({
                    url: "{{ route('checkout.get.states') }}",
                    type: "GET",
                    data: {
                        country_id: country_id
                    },
                    success: function(response) {
                        var options = '<option value="">Select State</option>';
                        $.each(response, function(key, value) {
                            options += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#state_id').html(options);
                        $('#city_id').html('<option value="">Select City</option>');
                    }
                });
            });

            $('#state_id').change(function() {
                var state_id = $(this).val();
                $.ajax({
                    url: "{{ route('checkout.get.cities') }}",
                    type: "GET",
                    data: {
                        state_id: state_id
                    },
                    success: function(response) {
                        var options = '<option value="">Select City</option>';
                        $.each(response, function(key, value) {
                            options += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#city_id').html(options);
                    }
                });
            });
        });
    </script>
@endpush
