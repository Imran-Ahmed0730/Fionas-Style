<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>POS Terminal - {{ getSetting('business_name') }}</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    @if(getSetting('site_favicon') != null)
        <link rel="icon" href="{{ asset(getSetting('site_favicon')) }}" type="image/x-icon" />
    @endif

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/pos.css') }}" />
</head>

<body>
    <header class="pos-header">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-icon btn-round btn-light btn-sm me-3">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h4 class="mb-0 fw-bold">POS Terminal</h4>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-white border shadow-sm d-none d-md-block" id="calcBtn">
                <i class="fa fa-calculator me-1 text-primary"></i> Calculator
            </button>
            <button class="btn btn-sm btn-white border shadow-sm" id="historyBtn">
                <i class="fa fa-history me-1 text-warning"></i> Orders
            </button>
            <div class="dropdown">
                <button class="btn btn-icon btn-round btn-light btn-sm" data-bs-toggle="dropdown">
                    <i class="fa fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text fw-bold">{{ Auth::user()->name }}</span></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="pos-wrapper">
        <!-- Left: Product Section -->
        <div class="pos-main">
            <!-- Filters Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="fa fa-search text-muted"></i></span>
                                <input type="text" id="productSearch" class="form-control border-start-0 ps-0 h-40"
                                    placeholder="Search products by name or SKU...">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="d-flex gap-2">
                                <select id="categoryFilter" class="form-select border-0 bg-light rounded-3">
                                    <option value="all">Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <select id="brandFilter" class="form-select border-0 bg-light rounded-3">
                                    <option value="all">Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <button id="refreshBtn" class="btn btn-light"><i class="fa fa-sync-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scrollable Products Grid -->
            <div class="product-grid custom-scroll" id="productGrid">
                <div class="grid-full text-center w-100 py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Initializing products...</p>
                </div>
            </div>
        </div>

        <!-- Right: Cart Sidebar -->
        <aside class="pos-sidebar" id="posSidebar">
            <!-- Customer Info -->
            <div class="p-2 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size: 10px;" class="fw-bold text-muted text-uppercase">Customer</span>
                    <div class="form-check p-0 form-switch ms-2">
                        <input class="form-check-switch" type="checkbox" id="walkInToggle" checked>
                        <label class="form-check-label" style="font-size: 11px;" for="walkInToggle">Walk-in</label>
                    </div>
                </div>

                <div id="customerSelectionWrap" class="d-none animate__animated animate__fadeIn">
                    <div class="d-flex gap-1 mb-1">
                        <div class="flex-grow-1">
                            <select id="customerSelect" class="form-select form-select-sm"></select>
                        </div>
                        <button class="btn btn-primary btn-sm py-1 px-2" data-bs-toggle="modal"
                            data-bs-target="#addCustomerModal" title="New Customer">
                            <i class="fa fa-plus" style="font-size: 10px;"></i>
                        </button>
                    </div>
                </div>

                <div id="walkInLabel"
                    class="d-flex justify-content-between align-items-center p-1 bg-light rounded-2 mb-0 border">
                    <span class="text-primary fw-bold" style="font-size: 11px;"><i class="fa fa-user-circle me-1"></i>
                        Walk-in</span>
                    <button class="btn btn-sm btn-outline-primary py-0 px-1" style="font-size: 10px;"
                        data-bs-toggle="modal" data-bs-target="#walkInInfoModal">
                        <i class="fa fa-edit"></i>
                    </button>
                </div>
            </div>

            <!-- Delivery & Shipping -->
            <div class="p-2 border-bottom bg-light-soft">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size: 10px;" class="fw-bold text-muted text-uppercase">Order Type</span>
                    <div class="btn-group btn-group-sm rounded-pill overflow-hidden border">
                        <input type="radio" class="btn-check" name="order_type" id="type_pickup" value="pickup" checked>
                        <label class="btn btn-white border-0 px-2 py-0" style="font-size: 11px;"
                            for="type_pickup">Pickup</label>
                        <input type="radio" class="btn-check" name="order_type" id="type_delivery" value="delivery">
                        <label class="btn btn-white border-0 px-2 py-0" style="font-size: 11px;"
                            for="type_delivery">Delivery</label>
                    </div>
                </div>

                <div id="shippingInfoWrap" class="d-none animate__animated animate__fadeIn">
                    <div class="row g-1 mb-1">
                        <div class="col-6">
                            <select id="shipCountry" class="form-select form-select-sm" style="font-size: 11px;">
                                <option value="">Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <select id="shipState" class="form-select form-select-sm" style="font-size: 11px;">
                                <option value="">State</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-1">
                        <div class="col-6">
                            <select id="shipCity" class="form-select form-select-sm" style="font-size: 11px;">
                                <option value="">City</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="text" id="shipAddress" class="form-control form-control-sm"
                                style="font-size: 11px;" placeholder="Address">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart List -->
            <div id="cartContent" class="cart-list custom-scroll">
                <div class="text-center py-5 mt-5 opacity-25">
                    <i class="fa fa-shopping-basket fa-4x mb-3"></i>
                    <p class="fw-bold">Cart is empty</p>
                </div>
            </div>

            <!-- Cart Footer / Totals -->
            <div class="p-2 bg-white border-top">
                <div class="mb-2">
                    <div class="input-group input-group-sm">
                        <input type="text" id="couponCode" class="form-control" style="font-size: 11px;"
                            placeholder="Coupon code">
                        <button class="btn btn-outline-success btn-sm" style="font-size: 10px;" id="applyCouponBtn"
                            type="button">
                            <i class="fa fa-tag"></i>
                        </button>
                    </div>
                    <div id="couponStatus" class="d-none mt-1">
                        <span id="couponMessage" class="text-success extra-small"></span>
                        <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-1" id="removeCouponBtn"
                            title="Remove Coupon">
                            <i class="fa fa-times-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted" style="font-size: 11px;">Subtotal</span>
                        <span class="fw-bold" style="font-size: 11px;" id="subtotalVal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-1" style="font-size: 11px;">Discount</span>
                            <button class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size: 9px;"
                                data-bs-toggle="modal" data-bs-target="#utilityModal" data-type="discount">Edit</button>
                        </div>
                        <span class="fw-bold text-danger" style="font-size: 11px;" id="discountVal">-$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted" style="font-size: 11px;">Shipping</span>
                        <span class="badge bg-info text-white" style="font-size: 8px;" id="shippingBadge">Auto</span>
                        <span class="fw-bold" style="font-size: 11px;" id="shippingVal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted" style="font-size: 11px;">Tax</span>
                        <span class="fw-bold" style="font-size: 11px;" id="taxVal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between border-top border-dashed pt-1 align-items-center">
                        <span class="mb-0 fw-bold text-dark" style="font-size: 12px;">TOTAL</span>
                        <span class="mb-0 fw-bold text-primary" style="font-size: 14px;" id="grandTotalVal">$0.00</span>
                    </div>
                </div>

                <div class="row g-1">
                    <div class="col-3">
                        <button id="clearCartBtn" class="btn btn-light w-100 py-2 border text-danger rounded-2"
                            title="Clear Cart" style="font-size: 11px;"> Clear
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="col-3">
                        <button id="holdBtn" class="btn btn-light w-100 py-2 border text-warning rounded-2"
                            title="Hold Order" style="font-size: 11px;"> Hold
                            <i class="fa fa-pause-circle"></i>
                        </button>
                    </div>
                    <div class="col-6">
                        <button id="checkoutBtn" class="pos-btn-pay h-100 fw-bold rounded-2 py-2"
                            style="font-size: 12px;">
                            Payment <i class="fa fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Floating Cart Toggle Button -->
        <button id="cartToggleBtn" class="cart-toggle-btn">
            <i class="fa fa-shopping-cart"></i>
            <span class="cart-badge" id="cartBadge">0</span>
        </button>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('backend/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    @include('backend.pos.partials.modals')
    @include('backend.pos.partials.order_history')

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        const POS_CONFIG = {
            routes: {
                products: "{{ route('admin.pos.products') }}",
                customers: "{{ route('admin.pos.customers') }}",
                customer_store: "{{ route('admin.pos.customer.store') }}",
                cart_add: "{{ route('admin.pos.cart.add') }}",
                cart_batch_add: "{{ route('admin.pos.cart.batch-add') }}",
                cart_update: "{{ route('admin.pos.cart.update') }}",
                cart_remove: "{{ url('admin/pos/cart/remove') }}",
                cart_clear: "{{ route('admin.pos.cart.clear') }}",
                cart_get: "{{ route('admin.pos.cart.get') }}",
                apply_coupon: "{{ route('admin.pos.apply-coupon') }}",
                place_order: "{{ route('admin.pos.place-order') }}",
                hold_order: "{{ route('admin.pos.hold-order') }}",
                hold_orders: "{{ route('admin.pos.hold-orders') }}",
                hold_orders_edit: "{{ url('admin/pos/hold-orders/edit') }}",
                hold_orders_update: "{{ url('admin/pos/hold-orders/update') }}",
                hold_orders_delete: "{{ url('admin/pos/hold-orders/delete') }}",
                recent_orders: "{{ route('admin.pos.recent-orders') }}",
                get_states: "{{ route('admin.customer.get-states', '') }}",
                get_cities: "{{ route('admin.customer.get-cities', '') }}",
                product_variants: "{{ url('admin/pos/product/variants') }}"
            },
            data: {
                payment_methods: @json($payment_methods)
            }
        };

        const POS = {
            cart: [],
            customer: { id: null, name: 'Walk-in Customer' },
            discount: { type: 'fixed', value: 0 },
            shipping: 0,
            tax: 0,
            order_type: 'pickup',
            shipping_info: { country_id: null, state_id: null, city_id: null, address: '' },
            walk_in_name: 'Walk-in Customer',
            walk_in_phone: 'N/A',
            coupon: null,
            shipping_manual: false,
            editing_order_id: null
        };
    </script>
    <script src="{{ asset('backend/assets/js/pos.js') }}"></script>
    <script>
        $(document).ready(function () {
            @if(Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            @if(Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
</body>

</html>