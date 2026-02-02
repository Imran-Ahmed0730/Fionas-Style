@extends('frontend.master')
@section('title', 'Cart')
@push('css')
    <style>
        .cart-table table tbody tr td.si-close {
            padding-bottom: 30px;
        }
        .cart-table table tbody tr td.si-close i {
            cursor: pointer;
        }
        .item-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #e7ab3c;
        }
        .select-all-wrap {
            margin-bottom: 20px;
            padding: 15px;
            background: #fdfdfd;
            border: 1px solid #ebebeb;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .select-all-wrap label {
            margin-bottom: 0;
            font-weight: 600;
            cursor: pointer;
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
                                        <input type="hidden" class="shipping-cost" value="{{ $item->attributes->shipping_cost }}">
                                        <input type="hidden" class="free-shipping" value="{{ $item->attributes->free_shipping }}">
                                        <input type="hidden" class="price" value="{{ $item->price }}">
                                    
                                        <td class="cart-pic first-row">
                                            <img src="{{ $item->attributes['image'] && file_exists(public_path($item->attributes['image'])) ? asset($item->attributes['image']) : asset('backend/assets/img/default-150x150.png') }}" alt="{{ $item->name }}"
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
                                        <td class="close-td first-row si-close">
                                            <i class="ti-close remove-from-cart"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <h5>Your cart is empty.</h5>
                                            <a href="{{ route('shop') }}" class="primary-btn mt-3">Go to Shop</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="cart-buttons">
                                <a href="{{ route('shop') }}" class="primary-btn continue-shop">Continue shopping</a>
                            </div>
                        </div>
                        <div class="col-lg-4 offset-lg-4">
                            <div class="proceed-checkout">
                                <ul>
                                    <li class="subtotal">Subtotal <span>{{ $currency['symbol'] ?? '$' }}<span id="subtotal">{{ number_format($subtotal, 2) }}</span></span></li>
                                    <li class="subtotal">Tax <span>{{ $currency['symbol'] ?? '$' }}<span id="tax">{{ number_format($tax, 2) }}</span></span></li>
                                    <li class="subtotal">Shipping <span>{{ $currency['symbol'] ?? '$' }}<span id="shippingCost">{{ number_format($shipping_cost, 2) }}</span></span></li>
                                    <li class="cart-total">Total <span>{{ $currency['symbol'] ?? '$' }}<span id="grandTotal">{{ number_format($grand_total, 2) }}</span></span></li>
                                </ul>
                                <a href="{{ route('checkout.index') }}" class="proceed-btn">PROCEED TO CHECK OUT</a>
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
        $(document).ready(function () {
            // Updated to handle standard cart behavior
        });
    </script>
@endpush