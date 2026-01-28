@extends('frontend.master')
@section('title', 'Home')
@push('css')
    <style>
        .product-category-section {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .product-category-section .section-title h2 {
            font-size: 36px;
            margin-bottom: 10px;
            text-transform: capitalize;
        }

        .product-category-section .section-title p {
            color: #666;
            margin-bottom: 40px;
        }


        .product-category-section .product-item {
            margin-bottom: 30px;
        }

        .hover-btns {
            opacity: 0;
            transition: all 0.3s;
        }

        .product-item:hover .hover-btns {
            opacity: 1;
        }

        @media (max-width: 991px) {
            .product-category-section .section-title h2 {
                font-size: 28px;
            }
        }
    </style>
@endpush

@section('content')
        <!-- Hero Section Begin -->
        <section class="hero-section">
            <div class="hero-items owl-carousel">
                @foreach($sliders as $slider)

                    <div class="single-hero-items set-bg" data-setbg="{{ asset($slider->image) }}">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <span>{{ $slider->subtitle }}</span>
                                    <h1>{{ $slider->title }}</h1>
                                    <p>{{ $slider->description }}</p>
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}" class="primary-btn">{{ $slider->btn_text ?? 'Shop Now' }}</a>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="off-card">
                                <h2>Sale <span>50%</span></h2>
                            </div> -->
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <!-- Hero Section End -->

        <!-- Banner Section Begin -->
        <div class="banner-section spad">
            <div class="container-fluid">
                <div class="row">
                    @foreach($featuredCategories as $category)
                        <div class="col-lg-4">
                            <div class="single-banner">
                                <img src="{{ $category->cover_photo ? asset($category->cover_photo) : asset('backend/assets/img/default-150x150.png') }}" alt="">
                                <div class="inner-text">
                                    <h4>{{ $category->name }}</h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Banner Section End -->

        <!-- Featured Products Section Begin -->
        <section class="women-banner spad">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="product-large set-bg"
                            data-setbg="{{ asset('frontend/assets/img/products/women-large.jpg') }}">
                            <h2>Featured</h2>
                            <a href="#">Discover More</a>
                        </div>
                    </div>
                    <div class="col-lg-8 offset-lg-1">
                        @php
    $featuredCats = $featuredProducts->pluck('category')->unique('id');
                        @endphp
                    <div class="filter-control">
                        <ul>
                            <li class="active" data-filter="*"><a data-toggle="tab" href="#feat-all">All</a></li>
                            @foreach($featuredCats as $cat)
                                <li data-filter=".{{ $cat->slug }}"><a data-toggle="tab" href="#feat-{{ $cat->id }}">{{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="feat-all" role="tabpanel">
                            <div class="product-slider owl-carousel">
                                @foreach($featuredProducts as $product)
                                    @include('frontend.include.product_item', ['product' => $product])
                                @endforeach
                            </div>
                        </div>
                        @foreach($featuredCats as $cat)
                            <div class="tab-pane fade" id="feat-{{ $cat->id }}" role="tabpanel">
                                <div class="product-slider owl-carousel">
                                    @foreach($featuredProducts->where('category_id', $cat->id) as $product)
                                        @include('frontend.include.product_item', ['product' => $product])
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Featured Products Section End -->

        @php
    $bgColors = ['', 'bg-light', ''];
        @endphp

        <!-- Category Sections Begin -->
        @foreach($categoryProducts as $index => $category)
            <section class="product-category-section spad {{ $bgColors[$index] ?? '' }}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title">
                                <h2>{{ $category->name }}'s Collection</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            <div class="category-product-slider owl-carousel">
                                @foreach($category->products as $product)
                                    @include('frontend.include.product_item', ['product' => $product])
                                @endforeach
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('category', $category->slug) }}" class="primary-btn">Shop {{ $category->name }}
                                    Collection</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
        <!-- Category Sections End -->

        <!-- Today's Deal Section Begin -->
        <section class="man-banner spad">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        @php
    $dealCats = $todaysDealProducts->pluck('category')->unique('id');
                        @endphp
                        <div class="filter-control">
                            <ul>
                                <li class="active"><a data-toggle="tab" href="#deal-all">All</a></li>
                                @foreach($dealCats as $cat)
                                    <li><a data-toggle="tab" href="#deal-{{ $cat->id }}">{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="deal-all" role="tabpanel">
                                <div class="product-slider owl-carousel">
                                    @foreach($todaysDealProducts as $product)
                                        @include('frontend.include.product_item', ['product' => $product])
                                    @endforeach
                                </div>
                            </div>
                            @foreach($dealCats as $cat)
                                <div class="tab-pane fade" id="deal-{{ $cat->id }}" role="tabpanel">
                                    <div class="product-slider owl-carousel">
                                        @foreach($todaysDealProducts->where('category_id', $cat->id) as $product)
                                            @include('frontend.include.product_item', ['product' => $product])
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1">
                        <div class="product-large set-bg m-large"
                            data-setbg="{{ asset('frontend/assets/img/products/man-large.jpg') }}">
                            <h2>Today's Deal</h2>
                            <a href="#">Discover More</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Today's Deal Section End -->

        @php
            $specDeal = $todaysDealProducts->first();
        @endphp
        @if($specDeal)
        <!-- Deal Of The Week Section Begin-->
        <section class="deal-of-week set-bg spad" data-setbg="{{ asset('frontend/assets/img/time-bg.jpg') }}">
            <div class="container">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h2>Deal Of The Week</h2>
                        <p>{{ $specDeal->short_description ?? Str::limit(strip_tags($specDeal->description), 100) }}</p>
                        <div class="product-price">
                            ${{ number_format($specDeal->final_price, 2) }}
                            <span>/ {{ $specDeal->category->name }}</span>
                        </div>
                    </div>
                    <div class="countdown-timer" id="countdown">
                        <div class="cd-item">
                            <span>0</span>
                            <p>Days</p>
                        </div>
                        <div class="cd-item">
                            <span>0</span>
                            <p>Hrs</p>
                        </div>
                        <div class="cd-item">
                            <span>0</span>
                            <p>Mins</p>
                        </div>
                        <div class="cd-item">
                            <span>0</span>
                            <p>Secs</p>
                        </div>
                    </div>
                    <a href="{{ route('product.details', $specDeal->slug) }}" class="primary-btn">Shop Now</a>
                </div>
            </div>
        </section>
        <!-- Deal Of The Week Section End -->
        @endif

        <!-- Latest Products Section Begin -->
        <section class="product-category-section spad bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>Latest Products</h2>
                            <p>Discover our newest additions to the collection</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="category-product-slider owl-carousel">
                            @foreach($latestProducts as $product)
                                @include('frontend.include.product_item', ['product' => $product])
                            @endforeach
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('shop') }}" class="primary-btn">View All Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Latest Products Section End -->

        <!-- Instagram Section Begin -->
        <div class="instagram-photo">
            <div class="insta-item set-bg" data-setbg="{{asset('frontend')}}/assets/img/insta-1.jpg">
                <div class="inside-text">
                    <i class="ti-instagram"></i>
                    <h5><a href="#">colorlib_Collection</a></h5>
                </div>
            </div>
            <div class="insta-item set-bg" data-setbg="{{asset('frontend')}}/assets/img/insta-2.jpg">
                <div class="inside-text">
                    <i class="ti-instagram"></i>
                    <h5><a href="#">colorlib_Collection</a></h5>
                </div>
            </div>
            <div class="insta-item set-bg" data-setbg="{{asset('frontend')}}/assets/img/insta-3.jpg">
                <div class="inside-text">
                    <i class="ti-instagram"></i>
                    <h5><a href="#">colorlib_Collection</a></h5>
                </div>
            </div>
            <div class="insta-item set-bg" data-setbg="{{asset('frontend')}}/assets/img/insta-4.jpg">
                <div class="inside-text">
                    <i class="ti-instagram"></i>
                    <h5><a href="#">colorlib_Collection</a></h5>
                </div>
            </div>
            <div class="insta-item set-bg" data-setbg="{{asset('frontend')}}/assets/img/insta-5.jpg">
                <div class="inside-text">
                    <i class="ti-instagram"></i>
                    <h5><a href="#">colorlib_Collection</a></h5>
                </div>
            </div>
            <div class="insta-item set-bg" data-setbg="{{asset('frontend')}}/assets/img/insta-6.jpg">
                <div class="inside-text">
                    <i class="ti-instagram"></i>
                    <h5><a href="#">colorlib_Collection</a></h5>
                </div>
            </div>
        </div>
        <!-- Instagram Section End -->

        <!-- Latest Blog Section Begin -->
        <section class="latest-blog spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>From The Blog</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($blogs as $blog)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-latest-blog">
                                <div class="blog-thumbnail">
                                    <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}">
                                </div>
                                <div class="latest-text">
                                    <div class="tag-list">
                                        <div class="tag-item">
                                            <i class="fa fa-calendar-o"></i>
                                            {{ $blog->created_date }}
                                        </div>
                                        <div class="tag-item">
                                            <i class="fa fa-comment-o"></i>
                                            5
                                        </div>
                                    </div>
                                    <a href="#">
                                        <h4>{{ $blog->title }}</h4>
                                    </a>
                                    <p>{{ Str::limit(strip_tags($blog->description), 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="benefit-items">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="single-benefit">
                                <div class="sb-icon"><img src="{{asset('frontend')}}/assets/img/icon-1.png" alt=""></div>
                                <div class="sb-text">
                                    <h6>Free Shipping</h6>
                                    <p>For all order over 99$</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="single-benefit">
                                <div class="sb-icon"><img src="{{asset('frontend')}}/assets/img/icon-2.png" alt=""></div>
                                <div class="sb-text">
                                    <h6>Delivery On Time</h6>
                                    <p>If good have prolems</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="single-benefit">
                                <div class="sb-icon"><img src="{{asset('frontend')}}/assets/img/icon-1.png" alt=""></div>
                                <div class="sb-text">
                                    <h6>Secure Payment</h6>
                                    <p>100% secure payment</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Latest Blog Section End -->

        <!-- Partner Logo Section Begin -->
        <div class="partner-logo">
            <div class="container">
                <div class="logo-carousel owl-carousel">
                    @foreach($brands as $brand)
                        <div class="logo-item">
                            <div class="tablecell-inner">
                                <img src="{{$brand->image ? asset($brand->image) : asset('backend/assets/img/default-150x150.png')}}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Partner Logo Section End -->
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            // Ensure clicking the tab area triggers the switch
            $('.filter-control ul li').on('click', function (e) {
                $(this).find('a').tab('show');
            });

            // Handle tab switch events
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var $target = $(e.target);
                var $parentUl = $target.closest('ul');
                
                // Manually sync active classes for both li and a tags
                $parentUl.find('li').removeClass('active');
                $parentUl.find('a').removeClass('active');
                
                $target.addClass('active');
                $target.closest('li').addClass('active');

                // Refresh carousels to fix visibility/layout issues
                $(".owl-carousel").trigger('refresh.owl.carousel');
            });
        });
    </script>
@endpush