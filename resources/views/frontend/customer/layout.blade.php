@extends('frontend.master')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="ti-home"></i> Home</a>
                        <span>Customer Dashboard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Customer Dashboard Section Begin -->
    <section class="customer-dashboard spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="customer-sidebar">
                        <div class="customer-profile-info text-center mb-4">
                            @php
                                $customer = auth()->user()->customer;
                                $profile_image = $customer && $customer->image ? asset($customer->image) : asset('backend/assets/img/profile.jpg');
                            @endphp
                            <div class="profile-pic mb-3">
                                <img src="{{ $profile_image }}" alt="{{ auth()->user()->name }}"
                                    class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <h5>{{ auth()->user()->name }}</h5>
                            <p class="text-muted small">{{ auth()->user()->email }}</p>
                        </div>
                        <ul class="customer-menu list-group">
                            <a href="{{ route('customer.dashboard') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                                <i class="ti-dashboard mr-2"></i> Dashboard Overview
                            </a>
                            <a href="{{ route('customer.orders') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('customer.orders') || request()->routeIs('customer.order.details') ? 'active' : '' }}">
                                <i class="ti-shopping-cart mr-2"></i> Order List
                            </a>
                            <a href="{{ route('customer.wishlist.index') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('customer.wishlist.index') ? 'active' : '' }}">
                                <i class="ti-heart mr-2"></i> Wishlist Management
                            </a>
                            <a href="{{ route('customer.profile') }}"
                                class="list-group-item list-group-item-action {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                                <i class="ti-user mr-2"></i> Profile Management
                            </a>
                            <a href="javascript:void(0)" onclick="confirmAccountDeletion()"
                                class="list-group-item list-group-item-action text-danger mt-3">
                                <i class="ti-trash mr-2"></i> Account Deletion
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action">
                                    <i class="ti-power-off mr-2"></i> Logout
                                </button>
                            </form>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="customer-content">
                        @yield('customer_content')
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Account Deletion Modal/Form -->
    <form id="delete-account-form" action="{{ route('customer.account.delete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('css')
    <style>
        .customer-sidebar .customer-menu .list-group-item {
            border-radius: 0;
            border: none;
            border-bottom: 1px solid #f2f2f2;
            padding: 15px 20px;
            font-weight: 500;
            color: #252525;
            transition: all 0.3s;
        }

        .customer-sidebar .customer-menu .list-group-item.active {
            background-color: #e7ab3c;
            border-color: #e7ab3c;
            color: #fff;
        }

        .customer-sidebar .customer-menu .list-group-item:hover:not(.active) {
            background-color: #f9f9f9;
            color: #e7ab3c;
        }

        .customer-content {
            padding: 20px;
            background: #fff;
            min-height: 400px;
        }
    </style>
@endpush

@push('js')
    <script>
        function confirmAccountDeletion() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                document.getElementById('delete-account-form').submit();
            }
        }
    </script>
@endpush
