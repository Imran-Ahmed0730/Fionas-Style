@php
    $route = Route::currentRouteName();
    $url = URL::current();
@endphp

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{route('admin.dashboard')}}" class="logo">
                @if(getSetting('site_logo') != null)
                    <img src="{{asset(getSetting('site_logo'))}}" alt="navbar brand" class="navbar-brand" height="70" />
                @else
                    <img src="{{asset('backend')}}/assets/img/kaiadmin/logo_light.svg" alt="navbar brand"
                        class="navbar-brand" height="70" />
                @endif

            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">

            <ul class="nav nav-secondary" id="sidebar-menu">
                <li class="nav-item {{$route == 'admin.dashboard' ? 'active' : ''}}">
                    <a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- CATALOG SECTION --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Catalog</h4>
                </li>

                @canany(['Product View', 'Product Add'])
                    <li class="nav-item {{Str::contains($route, 'product') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#product">
                            <i class="fas fa-archive"></i>
                            <p>Products</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'product') ? 'show' : ''}}" id="product">
                            <ul class="nav nav-collapse">
                                @can('Product Add')
                                    <li class="{{$route == 'admin.product.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.product.create')}}">
                                            <span class="sub-item">Add Product</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Product View')
                                    <li class="{{$route == 'admin.product.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.product.index')}}">
                                            <span class="sub-item">View Products</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Product Stock View', 'Product Stock Add'])
                    <li class="nav-item {{Str::contains($route, 'stock') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#product_stock">
                            <i class="fas fa-boxes-stacked"></i>
                            <p>Stock</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'stock') ? 'show' : ''}}" id="product_stock">
                            <ul class="nav nav-collapse">
                                @can('Product Stock Add')
                                    <li class="{{$route == 'admin.stock.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.stock.create')}}">
                                            <span class="sub-item">Add Stock</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Product Stock View')
                                    <li class="{{$route == 'admin.stock.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.stock.index')}}">
                                            <span class="sub-item">View Stock</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Category Add', 'Category View'])
                    <li
                        class="nav-item {{Str::contains($route, 'category') && !Str::contains($route, 'blog') && !Str::contains($route, 'faq') ? 'active' : ''}}">
                        {{-- Note: Added check to prevent active state on blog/faq categories --}}
                        <a data-bs-toggle="collapse" href="#category">
                            <i class="fas fa-layer-group"></i>
                            <p>Categories</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'category') && !Str::contains($route, 'blog') && !Str::contains($route, 'faq') ? 'show' : ''}}"
                            id="category">
                            <ul class="nav nav-collapse">
                                @can('Category Add')
                                    <li class="{{$route == 'admin.category.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.category.create')}}">
                                            <span class="sub-item">Add Category</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Category View')
                                    <li class="{{$route == 'admin.category.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.category.index')}}">
                                            <span class="sub-item">View Categories</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Brand Add', 'Brand View'])
                    <li class="nav-item {{Str::contains($route, 'brand') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#brand">
                            <i class="fas fa-star"></i>
                            <p>Brands</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'brand') ? 'show' : ''}}" id="brand">
                            <ul class="nav nav-collapse">
                                @can('Brand Add')
                                    <li class="{{$route == 'admin.brand.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.brand.create')}}">
                                            <span class="sub-item">Add Brand</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Brand View')
                                    <li class="{{$route == 'admin.brand.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.brand.index')}}">
                                            <span class="sub-item">View Brands</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Attribute Add', 'Attribute View'])
                    <li class="nav-item {{Str::contains($route, 'attribute') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#attribute">
                            <i class="fa-solid fa-network-wired"></i>
                            <p>Attributes</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'attribute') ? 'show' : ''}}" id="attribute">
                            <ul class="nav nav-collapse">
                                @can('Attribute Add')
                                    <li class="{{$route == 'admin.attribute.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.attribute.create')}}">
                                            <span class="sub-item">Add Attribute</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Attribute View')
                                    <li class="{{$route == 'admin.attribute.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.attribute.index')}}">
                                            <span class="sub-item">View Attributes</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Color Add', 'Color View'])
                    <li class="nav-item {{Str::contains($route, 'color') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#color">
                            <i class="fas fa-eye-dropper"></i>
                            <p>Colors</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'color') ? 'show' : ''}}" id="color">
                            <ul class="nav nav-collapse">
                                @can('Color Add')
                                    <li class="{{$route == 'admin.color.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.color.create')}}">
                                            <span class="sub-item">Add Color</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Color View')
                                    <li class="{{$route == 'admin.color.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.color.index')}}">
                                            <span class="sub-item">View Colors</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Unit Add', 'Unit View'])
                    <li class="nav-item {{Str::contains($route, 'unit') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#unit">
                            <i class="fas fa-scale-balanced"></i>
                            <p>Units</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'unit') ? 'show' : ''}}" id="unit">
                            <ul class="nav nav-collapse">
                                @can('Unit Add')
                                    <li class="{{$route == 'admin.unit.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.unit.create')}}">
                                            <span class="sub-item">Add Unit</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Unit View')
                                    <li class="{{$route == 'admin.unit.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.unit.index')}}">
                                            <span class="sub-item">View Units</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Supplier Add', 'Supplier View'])
                    <li class="nav-item {{Str::contains($route, 'supplier') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#supplier">
                            <i class="fa-solid fa-truck-field"></i>
                            <p>Suppliers</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'supplier') ? 'show' : ''}}" id="supplier">
                            <ul class="nav nav-collapse">
                                @can('Supplier Add')
                                    <li class="{{$route == 'admin.supplier.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.supplier.create')}}">
                                            <span class="sub-item">Add Supplier</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Supplier View')
                                    <li class="{{$route == 'admin.supplier.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.supplier.index')}}">
                                            <span class="sub-item">View Suppliers</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany


                {{-- SALES SECTION --}}
                @canany(['Order View', 'Payment Method View'])
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Sales</h4>
                </li>

                @canany(['Order Online View', 'Order POS View'])
                    <li
                        class="nav-item {{Str::contains($route, 'order') && !Str::contains($route, 'setting') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#orders">
                            <i class="fas fa-shopping-cart"></i>
                            <p>Orders</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'order') && !Str::contains($route, 'setting') ? 'show' : ''}}"
                            id="orders">
                            <ul class="nav nav-collapse">
                                @can('Order Online View')
                                    <li class="{{$route == 'admin.order.online' ? 'active' : ''}}">
                                        <a href="{{route('admin.order.online')}}">
                                            <span class="sub-item">Online Orders</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Order POS View')
                                    <li class="{{$route == 'admin.order.pos' ? 'active' : ''}}">
                                        <a href="{{route('admin.order.pos')}}">
                                            <span class="sub-item">POS Orders</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('Payment Method View')
                        <li class="nav-item {{Str::contains($route, 'payment-method') ? 'active' : ''}}">
                            <a href="{{route('admin.payment-method.index')}}">
                                <i class="fas fa-credit-card"></i>
                                <p>Payment Methods</p>
                            </a>
                        </li>
                    @endcan
                @endcanany


                {{-- MARKETING SECTION --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Marketing</h4>
                </li>

                @canany(['Campaign View', 'Campaign Add'])
                    <li class="nav-item {{Str::contains($route, 'campaign') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#campaign">
                            <i class="fas fa-percent"></i>
                            <p>Campaigns</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'campaign') ? 'show' : ''}}" id="campaign">
                            <ul class="nav nav-collapse">
                                @can('Campaign Add')
                                    <li class="{{$route == 'admin.campaign.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.campaign.create')}}">
                                            <span class="sub-item">Add Campaign</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Campaign View')
                                    <li class="{{$route == 'admin.campaign.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.campaign.index')}}">
                                            <span class="sub-item">View Campaigns</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Coupon View', 'Coupon Add'])
                    <li class="nav-item {{Str::contains($route, 'coupon') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#coupon">
                            <i class="fas fa-gift"></i>
                            <p>Coupons</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'coupon') ? 'show' : ''}}" id="coupon">
                            <ul class="nav nav-collapse">
                                @can('Coupon Add')
                                    <li class="{{$route == 'admin.coupon.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.coupon.create')}}">
                                            <span class="sub-item">Add Coupon</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Coupon View')
                                    <li class="{{$route == 'admin.coupon.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.coupon.index')}}">
                                            <span class="sub-item">View Coupons</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Banner Add', 'Banner View'])
                    <li class="nav-item {{Str::contains($route, 'banner') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#banner">
                            <i class="fas fa-image"></i>
                            <p>Banners</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'banner') ? 'show' : ''}}" id="banner">
                            <ul class="nav nav-collapse">
                                @can('Banner Add')
                                    <li class="{{$route == 'admin.banner.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.banner.create')}}">
                                            <span class="sub-item">Add Banner</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Banner View')
                                    <li class="{{$route == 'admin.banner.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.banner.index')}}">
                                            <span class="sub-item">View Banners</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Slider Add', 'Slider View'])
                    <li class="nav-item {{Str::contains($route, 'slider') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#slider">
                            <i class="fas fa-sliders"></i>
                            <p>Sliders</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'slider') ? 'show' : ''}}" id="slider">
                            <ul class="nav nav-collapse">
                                @can('Slider Add')
                                    <li class="{{$route == 'admin.slider.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.slider.create')}}">
                                            <span class="sub-item">Add Slider</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Slider View')
                                    <li class="{{$route == 'admin.slider.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.slider.index')}}">
                                            <span class="sub-item">View Sliders</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany


                {{-- CONTENT SECTION --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Content</h4>
                </li>

                @canany(['Blog Category View', 'Blog View'])
                    <li class="nav-item {{Str::contains($route, 'blog') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#blog">
                            <i class="fas fa-newspaper"></i>
                            <p>Blog</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'blog') ? 'show' : ''}}" id="blog">
                            <ul class="nav nav-collapse">
                                @can('Blog Category View')
                                    <li class="{{$route == 'admin.blog.category.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.blog.category.index')}}">
                                            <span class="sub-item">Categories</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Blog View')
                                    <li class="{{$route == 'admin.blog.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.blog.index')}}">
                                            <span class="sub-item">Blogs</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['FAQ Category View', 'FAQ View'])
                    <li class="nav-item {{Str::contains($route, 'faq') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#faq">
                            <i class="fas fa-question"></i>
                            <p>FAQ</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'faq') ? 'show' : ''}}" id="faq">
                            <ul class="nav nav-collapse">
                                @can('FAQ Category View')
                                    <li class="{{$route == 'admin.faq.category.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.faq.category.index')}}">
                                            <span class="sub-item">Categories</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('FAQ View')
                                    <li class="{{$route == 'admin.faq.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.faq.index')}}">
                                            <span class="sub-item">FAQs</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['Page Add', 'Page View'])
                    <li class="nav-item {{Str::contains($route, 'page') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#page">
                            <i class="fas fa-file"></i>
                            <p>Pages</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'page') ? 'show' : ''}}" id="page">
                            <ul class="nav nav-collapse">
                                @can('Page Add')
                                    <li class="{{$route == 'admin.page.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.page.create')}}">
                                            <span class="sub-item">Add Page</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Page View')
                                    <li class="{{$route == 'admin.page.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.page.index')}}">
                                            <span class="sub-item">View Pages</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany


                {{-- ACCOUNTS SECTION --}}
                @canany(['Account Head View', 'Account Balance Sheet View', 'Account Cashbook View', 'Sales Report View'])
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Accounts</h4>
                    </li>

                    @can('Account Head View')
                        <li class="nav-item {{Str::contains($route, 'account-head') ? 'active' : ''}}">
                            <a href="{{route('admin.account-head.index')}}">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <p>Account Heads</p>
                            </a>
                        </li>
                    @endcan

                    @canany(['Account Balance Sheet View', 'Account Cashbook View', 'Sales Report View'])
                        <li class="nav-item {{Str::contains($route, 'account-report') ? 'active' : ''}}">
                            <a data-bs-toggle="collapse" href="#account_report">
                                <i class="fas fa-chart-line"></i>
                                <p>Account Reports</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse {{Str::contains($route, 'account-report') ? 'show' : ''}}" id="account_report">
                                <ul class="nav nav-collapse">
                                    @can('Account Balance Sheet View')
                                        <li class="{{$route == 'admin.account-report.balance-sheet' ? 'active' : ''}}">
                                            <a href="{{route('admin.account-report.balance-sheet')}}">
                                                <span class="sub-item">Balance Sheet</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Account Cashbook View')
                                        <li class="{{$route == 'admin.account-report.cashbook' ? 'active' : ''}}">
                                            <a href="{{route('admin.account-report.cashbook')}}">
                                                <span class="sub-item">Cashbook</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Sales Report View')
                                        <li class="{{$route == 'admin.account-report.sales-report' ? 'active' : ''}}">
                                            <a href="{{route('admin.account-report.sales-report')}}">
                                                <span class="sub-item">Sales Report</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany
                @endcanany


                {{-- USERS SECTION --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">User Management</h4>
                </li>

                @canany(['Staff Create', 'Staff View'])
                    <li class="nav-item {{Str::contains($route, 'staff') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#staff">
                            <i class="fas fa-user-friends"></i>
                            <p>Staffs</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'staff') ? 'show' : ''}}" id="staff">
                            <ul class="nav nav-collapse">
                                @can('Staff Create')
                                    <li class="{{$route == 'admin.staff.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.staff.create')}}">
                                            <span class="sub-item">Add Staff</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Staff View')
                                    <li class="{{$route == 'admin.staff.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.staff.index')}}">
                                            <span class="sub-item">View Staffs</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @can('Subscriber View')
                    <li class="nav-item {{$route == 'admin.subscriber.index' ? 'active' : ''}}">
                        <a href="{{route('admin.subscriber.index')}}"><i class="fas fa-envelope"></i>
                            <p>Subscribers</p>
                        </a>
                    </li>
                @endcan

                @canany(['Role Add', 'Role View', 'Permission Add', 'Permission View'])
                    <li
                        class="nav-item {{Str::contains($route, 'role') || Str::contains($route, 'permission') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#role-permission">
                            <i class="fas fa-users-cog"></i>
                            <p>Roles & Permissions</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'role') || Str::contains($route, 'permission') ? 'show' : ''}}"
                            id="role-permission">
                            <ul class="nav nav-collapse">
                                @canany(['Role Add', 'Role View'])
                                    <li>
                                        <a data-bs-toggle="collapse" href="#role">
                                            <span class="sub-item">Roles</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse {{Str::contains($route, 'role') ? 'show' : ''}}" id="role">
                                            <ul class="nav nav-collapse subnav">
                                                @can('Role Add')
                                                    <li class="{{$route == 'admin.role.create' ? 'active' : ''}}">
                                                        <a href="{{route('admin.role.create')}}">
                                                            <span class="sub-item">Add Role</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('Role View')
                                                    <li class="{{$route == 'admin.role.index' ? 'active' : ''}}">
                                                        <a href="{{route('admin.role.index')}}">
                                                            <span class="sub-item">View Roles</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcanany
                                @canany(['Permission Add', 'Permission View'])
                                    <li>
                                        <a data-bs-toggle="collapse" href="#permission">
                                            <span class="sub-item">Permission</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse {{Str::contains($route, 'permission') ? 'show' : ''}}"
                                            id="permission">
                                            <ul class="nav nav-collapse subnav">
                                                @can('Permission Add')
                                                    <li class="{{$route == 'admin.permission.create' ? 'active' : ''}}">
                                                        <a href="{{route('admin.permission.create')}}">
                                                            <span class="sub-item">Add Permission</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('Permission View')
                                                    <li class="{{$route == 'admin.permssion.index' ? 'active' : ''}}">
                                                        <a href="{{route('admin.permission.index')}}">
                                                            <span class="sub-item">View Permissions</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcanany
                            </ul>
                        </div>
                    </li>
                @endcanany


                {{-- SETTINGS SECTION --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Settings</h4>
                </li>

                @canany(['Settings Add', 'Settings View', 'Settings Site', 'Settings Social Media', 'Settings Logo & Favicon', 'Settings Contact', 'Settings Store', 'Settings Order', 'Settings Activation'])
                    <li class="nav-item {{Str::contains($route, 'setting') ? 'active' : ''}}">
                        <a data-bs-toggle="collapse" href="#setting">
                            <i class="fas fa-cogs"></i>
                            <p>Settings</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'setting') ? 'show' : ''}}" id="setting">
                            <ul class="nav nav-collapse">
                                @can('Settings View')
                                    <li class="{{$route == 'admin.setting.index' ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.index')}}">
                                            <span class="sub-item">View Keys</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Add')
                                    <li class="{{$route == 'admin.setting.create' ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.create')}}">
                                            <span class="sub-item">Add Key</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Site')
                                    <li class="{{Str::contains($url, 'site') ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.edit', 'site')}}">
                                            <span class="sub-item">Site Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Social Media')
                                    <li class="{{Str::contains($url, 'social-media') ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.edit', 'social-media')}}">
                                            <span class="sub-item">Social Media</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Logo & Favicon')
                                    <li class="{{Str::contains($url, 'logos-favicon') ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.edit', 'logos-favicon')}}">
                                            <span class="sub-item">Logo & Favicon</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Contact')
                                    <li class="{{Str::contains($url, 'contact') ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.edit', 'contact')}}">
                                            <span class="sub-item">Contact Info</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Order')
                                    <li class="{{Str::contains($url, 'order') ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.edit', 'order')}}">
                                            <span class="sub-item">Order Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Store')
                                    <li class="{{Str::contains($url, 'store') ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.edit', 'store')}}">
                                            <span class="sub-item">Store Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Activation')
                                    <li class="{{Str::contains($url, 'activation') ? 'active' : ''}}">
                                        <a href="{{route('admin.setting.edit', 'activation')}}">
                                            <span class="sub-item">Activations</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->