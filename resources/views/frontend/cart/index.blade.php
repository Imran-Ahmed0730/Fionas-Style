@extends('frontend.master')
@section('title', 'Cart')
@section('content')

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="{{ route('shop') }}">Shop</a>
                        <span>Shopping Cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container cart_item_wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cart-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th class="p-name">Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th><i class="ti-close"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr class="cart_item cart-item-{{ $item->id }}" data-sku="{{ $item->id }}">
                                        <!-- Hidden Inputs for JS Logic -->
                                        <input type="hidden" class="tax" value="{{ $item->attributes->tax }}">
                                        <input type="hidden" class="shipping-cost"
                                            value="{{ $item->attributes->shipping_cost }}">
                                        <input type="hidden" class="free-shipping"
                                            value="{{ $item->attributes->free_shipping }}">
                                        <input type="hidden" class="price" value="{{ $item->price }}">

                                    
                                        <td class="cart-pic first-row">
                                            <img src="{{ $item->attributes['image'] && file_exists($item->attributes['image']) ? asset($item->attributes['image']) : asset('backend/assets/img/default-150x150.png') }}" alt="{{ $item->name }}"
                                                style="max-width: 100px;">
                                        </td>
                                        <td class="cart-title first-row">
                                            <h5>{{ $item->name }}</h5>
                                            @if($item->attributes->variant)
                                                <p><small>{{ $item->attributes->variant }}</small></p>
                                            @endif
                                        </td>
                                        <td class="p-price first-row">
                                            {{ $currency['symbol'] ?? '$' }}{{ number_format($item->price, 2) }}</td>
                                        <td class="qua-col first-row">
                                            <div class="quantity">
                                                <!-- Manual Structure to avoid main.js conflict but match style -->
                                                <div class="pro-qty-custom"
                                                    style="width: 123px; height: 46px; border: 2px solid #ebebeb; padding: 0 15px; float: left; border-radius: 5px;">
                                                    <span class="dec qtybtn btn_decrease"
                                                        style="font-size: 24px; color: #b2b2b2; float: left; line-height: 40px; cursor: pointer;">-</span>
                                                    <input type="text" class="quantity" value="{{ $item->quantity }}" readonly
                                                        style="text-align: center; width: 50px; font-size: 16px; font-weight: 700; border: none; height: 100%; float: left; color: #4c4c4c;">
                                                    <span class="inc qtybtn btn_increase"
                                                        style="font-size: 24px; color: #b2b2b2; float: right; line-height: 40px; cursor: pointer;">+</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="total-price first-row">
                                            {{ $currency['symbol'] ?? '$' }}<span
                                                class="item-total">{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </td>
                                        <td class="close-td first-row">
                                            <i class="ti-close remove-from-cart" style="cursor: pointer;"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Your cart is empty.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="cart-buttons">
                                <a href="{{ route('shop') }}" class="primary-btn continue-shop">Continue shopping</a>
                                {{-- <a href="#" class="primary-btn up-cart">Update cart</a> --}}
                            </div>
                            {{-- Optional Coupon Section --}}
                            {{-- <div class="discount-coupon">
                                <h6>Discount Codes</h6>
                                <form action="#" class="coupon-form">
                                    <input type="text" placeholder="Enter your codes">
                                    <button type="submit" class="site-btn coupon-btn">Apply</button>
                                </form>
                            </div> --}}
                        </div>
                        <div class="col-lg-4 offset-lg-4">
                            <div class="proceed-checkout">
                                <ul>
                                    <li class="subtotal">Subtotal <span>{{ $currency['symbol'] ?? '$' }}<span
                                                id="subtotal">0.00</span></span></li>
                                    <li class="subtotal">Tax <span>{{ $currency['symbol'] ?? '$' }}<span
                                                id="tax">0.00</span></span></li>
                                    <li class="subtotal">Shipping <span>{{ $currency['symbol'] ?? '$' }}<span
                                                id="shippingCost">0.00</span></span></li>
                                    <li class="cart-total">Total <span>{{ $currency['symbol'] ?? '$' }}<span
                                                id="grandTotal">0.00</span></span></li>
                                </ul>
                                <a href="{{ route('checkout.index') }}" class="proceed-btn">PROCEED TO CHECK OUT (<span
                                        class="item_for_checkout">0</span>)</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->

@endsection

@push('js')
    <script>
        // Initial Calculation on Page Load
        $(document).ready(function () {
            DisplayCountOfCheckedItems();
        });

        function toggleAllItems() {
            let isChecked = $('#totalItemsCheckbox').is(':checked');
            $('.item-checkbox').prop('checked', isChecked);

            // Trigger status update for all (optional, or just update UI total)
            // Ideally we iterate and send AJAX or have a bulk update route.
            // For now, let's just update the UI totals logic.
            // But if backend requires 'check_for_order_placement' to be saved, we need AJAX loops or a batch endpoint.
            // Given complexity, update UI first.

            $('.item-checkbox').each(function () {
                let sku = $(this).closest('.cart_item').data('sku');
                let status = isChecked ? 1 : 0;
                // Call status update backend
                $.ajax({
                    method: 'POST',
                    url: '{{ route("cart.update.check.status") }}', // You need to uncomment this route in web.php if used
                    data: {
                        sku: sku,
                        check_status: status,
                        _token: '{{ csrf_token() }}'
                    }
                });
            });

            DisplayCountOfCheckedItems();
        }

        // Bind individual checkbox click to backend update
        $(document).on('click', '.item-checkbox', function () {
            let sku = $(this).closest('.cart_item').data('sku');
            let status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                method: 'POST', // or GET depending on route definition, usually POST
                url: '{{ route("cart.update.check.status") }}', // Ensure this route exists!
                data: {
                    sku: sku,
                    check_status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    DisplayCountOfCheckedItems();
                }
            });
        });

    </script>
@endpush