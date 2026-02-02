@extends('frontend.master')
@section('title', $item->name)
@push('css')
    <style>
        .category-header {
            position: relative;
            margin-bottom: 30px;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 10px;
            overflow: hidden;
            background-color: #f8f8f8;
        }

        .category-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .category-banner-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-content {
            position: relative;
            z-index: 2;
            color: #fff;
            padding: 0 15px;
            max-width: 800px;
        }

        .category-content h2 {
            color: #ffffff;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .category-content p {
            color: #efefef;
            font-size: 16px;
            line-height: 24px;
        }

        /* Subcategory Slider Styles */
        .subcategory-slider-wrap {
            margin-bottom: 40px;
        }

        .subcategory-item {
            background: #ffffff;
            border: 1px solid #ebebeb;
            border-radius: 8px;
            padding: 10px;
            display: flex !important;
            align-items: center;
            transition: all 0.3s;
            text-decoration: none !important;
            margin: 5px;
        }

        .subcategory-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: #e7ab3c;
        }

        .sub-img {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
            background: #f8f8f8;
        }

        .sub-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sub-text {
            padding-left: 15px;
        }

        .sub-text h6 {
            color: #252525;
            font-weight: 600;
            margin: 0;
            font-size: 14px;
            line-height: 1.2;
        }

        /* Slider Nav */
        .owl-nav button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #fff !important;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            font-size: 20px !important;
            color: #252525 !important;
            transition: 0.3s;
        }

        .owl-nav button:hover {
            background: #e7ab3c !important;
            color: #fff !important;
        }

        .owl-nav .owl-prev {
            left: -20px;
        }

        .owl-nav .owl-next {
            right: -20px;
        }
    </style>
@endpush
@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>{{ $item->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Category Header Section (Top, Full Width Container) -->
    <div class="container mt-4">
        <div class="category-header">
            @if ($item->cover_photo && file_exists(public_path($item->cover_photo)))
                <img src="{{ asset($item->cover_photo) }}" class="category-banner-img" alt="{{ $item->name }}">
            @else
                <div class="category-banner-img bg-secondary"></div>
            @endif
            <div class="category-content">
                <h2>{{ $item->name }}</h2>
                @if ($item->description)
                    <p>{!! $item->description !!}</p>
                @endif
            </div>
        </div>

        <!-- Subcategories Slider (Under Banner) -->
        @if ($item->subcategories->count() > 0)
            <div class="subcategory-slider-wrap">
                <div class="subcategory-slider owl-carousel">
                    @foreach ($item->subcategories as $sub)
                        <a href="{{ route('category', $sub->slug) }}" class="subcategory-item">
                            <div class="sub-img">
                                <img src="{{ $sub->cover_photo && file_exists(public_path($sub->cover_photo)) ? asset($sub->cover_photo) : asset('backend/assets/img/default-150x150.png') }}"
                                    alt="{{ $sub->name }}">
                            </div>
                            <div class="sub-text">
                                <h6>{{ $sub->name }}</h6>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Filter and Product Section Begin -->
    <section class="product-shop spad pt-0">
        <div class="container">
            <div class="row">
                @include('frontend.product.partials.sidebar', ['route' => url()->current()])
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="product-show-option mt-0">
                        <div class="row">
                            <div class="col-lg-7 col-md-7">
                                <form action="{{ url()->current() }}" method="GET" class="select-option">
                                    @foreach (request()->except('sort') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select class="sorting" name="sort" onchange="this.form.submit()">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Default
                                            Sorting (Latest)</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                            Price: Low to High</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                            Price: High to Low</option>
                                    </select>
                                </form>
                            </div>
                            <div class="col-lg-5 col-md-5 text-right">
                                <p>Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} Of
                                    {{ $products->total() }} Product
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="product-list">
                        <div class="row">
                            @forelse($products as $product)
                                <div class="col-lg-4 col-sm-6">
                                    @include('frontend.product.partials.product_item', ['product' => $product])
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">No products found in this category.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="loading-more">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $(".subcategory-slider").owlCarousel({
                loop: true,
                margin: 15,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    992: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                }
            });
        });
    </script>
@endpush