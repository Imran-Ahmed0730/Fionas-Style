@extends('frontend.master')
@section('title', 'Checkout')
@section('content')

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="{{ route('shop') }}">Shop</a>
                        <span>Check Out</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Checkout Section Begin -->
    <section class="checkout-section spad">
        <div class="container">
            <form action="{{ route('checkout.place.order') }}" method="POST" class="checkout-form">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        @guest
                            <div class="checkout-content">
                                <a href="{{ route('login') }}" class="content-btn">Click Here To Login</a>
                            </div>
                        @endguest
                        <h4>Biiling Details</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="user_name">Full Name<span>*</span></label>
                                <input type="text" id="user_name" name="name" placeholder="Full Name" class="@error('name') is-invalid @enderror"
                                    required
                                    value="{{ old('name', auth()->user() && auth()->user()->customer ? auth()->user()->customer->name : '') }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="cun">Country<span>*</span></label>
                                <select id="cun" name="country_id"
                                    class="w-100 mb-3 @error('country_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}" 
                                            {{ old('country_id', auth()->user() && auth()->user()->customer ? auth()->user()->customer->country_id : '') == $country['id'] ? 'selected' : '' }}>
                                            {{ $country['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="state">State<span>*</span></label>
                                <select id="state" name="state_id"
                                    class="w-100 mb-3 @error('state_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" 
                                            {{ old('state_id', auth()->user() && auth()->user()->customer ? auth()->user()->customer->state_id : '') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="town">Town / City<span>*</span></label>
                                <select id="town" name="city" class="w-100 mb-3 @error('city') is-invalid @enderror" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" 
                                            {{ old('city', auth()->user() && auth()->user()->customer ? auth()->user()->customer->city_id : '') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="street">Street Address<span>*</span></label>
                                <input type="text" id="street" name="address"
                                    class="street-first @error('address') is-invalid @enderror" required
                                    placeholder="House number and street name"
                                    value="{{ old('address', auth()->user() && auth()->user()->customer ? auth()->user()->customer->address : '') }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="@error('email') is-invalid @enderror" placeholder="Email Address"
                                    value="{{ old('email', auth()->user() && auth()->user()->customer ? auth()->user()->customer->email : '') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="phone">Phone<span>*</span></label>
                                <input type="tel" id="phone" name="phone" placeholder="Phone Number" class="@error('phone') is-invalid @enderror"
                                    required
                                    value="{{ old('phone', auth()->user() && auth()->user()->customer ? auth()->user()->customer->phone : '') }}">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="note">Order Notes (Optional)</label>
                                <textarea id="note" name="note" rows="4" placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="checkout-content">
                            <div class="input-group">
                                <input type="text" placeholder="Enter Your Coupon Code" name="coupon_code" id="coupon_code" class="form-control"
                                    value="{{ $coupon_code }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-dark" id="apply_coupon_btn" style="height: 46px; border-radius: 0;">Apply</button>
                                </div>
                            </div>
                            <div id="coupon_status" class="mt-2">
                                @if($coupon_code)
                                    <span class="text-success">Coupon applied! <a href="javascript:void(0)"
                                            id="remove_coupon_btn" class="text-danger ml-2">Remove</a></span>
                                @endif
                            </div>
                        </div>
                        <div class="place-order">
                            <h4>Your Order</h4>
                            <div class="order-total">
                                <ul class="order-table" id="order_summary">
                                    <li>Product <span>Total</span></li>
                                    @foreach($items as $item)
                                        <li class="fw-normal">{{ $item->name }} x {{ $item->quantity }}
                                            <span>{{ $currency['symbol'] ?? '$' }}{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                    <li class="fw-normal border-top pt-2">Subtotal
                                        <span
                                            id="summary_subtotal">{{ $currency['symbol'] ?? '$' }}{{ number_format($subtotal, 2) }}</span>
                                    </li>
                                    <li class="fw-normal">Tax
                                        <span
                                            id="summary_tax">{{ $currency['symbol'] ?? '$' }}{{ number_format($tax, 2) }}</span>
                                    </li>
                                    <li class="fw-normal">Shipping
                                        <span
                                            id="summary_shipping">{{ $currency['symbol'] ?? '$' }}{{ number_format($shipping_cost, 2) }}</span>
                                    </li>
                                    <li class="fw-normal" id="summary_discount_row"
                                        style="{{ ($discount ?? 0) > 0 ? '' : 'display: none;' }}">Discount
                                        <span id="summary_discount"
                                            class="text-danger">{{ $currency['symbol'] ?? '$' }}{{ number_format($discount ?? 0, 2) }}</span>
                                    </li>
                                    <li class="total-price">Total
                                        <span
                                            id="summary_total">{{ $currency['symbol'] ?? '$' }}{{ number_format($grand_total, 2) }}</span>
                                    </li>
                                </ul>
                                <div class="payment-check">
                                    <div class="pc-item">
                                        <label for="pc-cod">
                                            Cash On Delivery
                                            <input type="checkbox" id="pc-cod" name="payment_method" value="cod" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="order-btn">
                                    <button type="submit" class="site-btn place-btn">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Checkout Section End -->

@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Change Country -> Load States
            $('#cun').on('change', function () {
                let countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: "{{ route('checkout.get.states') }}",
                        type: "GET",
                        data: { country_id: countryId },
                        success: function (data) {
                            $('#state').empty().append('<option value="">Select State</option>');
                            $('#town').empty().append('<option value="">Select City</option>');
                            $.each(data, function (key, value) {
                                $('#state').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Change State -> Load Cities & Update Shipping
            $('#state').on('change', function () {
                let stateId = $(this).val();
                if (stateId) {
                    $.ajax({
                        url: "{{ route('checkout.get.cities') }}",
                        type: "GET",
                        data: { state_id: stateId },
                        success: function (response) {
                            $('#town').empty().append('<option value="">Select City</option>');
                            $.each(response.cities, function (key, value) {
                                $('#town').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            updateSummary(response.summary);
                        }
                    });
                }
            });

            // Single selection for payment methods
            $('input[name="payment_method"]').on('change', function () {
                $('input[name="payment_method"]').not(this).prop('checked', false);
            });

            // Apply Coupon
            $('#apply_coupon_btn').on('click', function () {
                let couponCode = $('#coupon_code').val();
                if (!couponCode) {
                    toastr.error('Please enter a coupon code');
                    return;
                }

                $.ajax({
                    url: "{{ route('checkout.apply.coupon') }}",
                    type: "POST",
                    data: {
                        coupon_code: couponCode,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        toastr.success(response.success);
                        $('#coupon_status').html('<span class="text-success">Coupon applied! <a href="javascript:void(0)" id="remove_coupon_btn" class="text-danger ml-2">Remove</a></span>');
                        updateSummary(response.summary);
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.error);
                    }
                });
            });

            // Remove Coupon
            $(document).on('click', '#remove_coupon_btn', function () {
                $.ajax({
                    url: "{{ route('checkout.remove.coupon') }}",
                    type: "GET",
                    success: function (response) {
                        toastr.success(response.success);
                        $('#coupon_code').val('');
                        $('#coupon_status').empty();
                        updateSummary(response.summary);
                    }
                });
            });

            function updateSummary(summary) {
                let symbol = "{{ $currency['symbol'] ?? '$' }}";
                $('#summary_subtotal').text(symbol + parseFloat(summary.subtotal).toFixed(2));
                $('#summary_tax').text(symbol + parseFloat(summary.tax).toFixed(2));
                $('#summary_shipping').text(symbol + parseFloat(summary.shipping_cost).toFixed(2));
                $('#summary_discount').text(symbol + parseFloat(summary.discount).toFixed(2));
                $('#summary_total').text(symbol + parseFloat(summary.grand_total).toFixed(2));

                if (parseFloat(summary.discount) > 0) {
                    $('#summary_discount_row').show();
                } else {
                    $('#summary_discount_row').hide();
                }
            }
        });
    </script>
@endpush