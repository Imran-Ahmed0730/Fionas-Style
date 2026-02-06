@extends('frontend.master')
@section('title', 'Home')
@section('meta_title', 'Home')
@section('meta_description', getSetting('meta_description'))
@section('meta_keywords', getSetting('meta_keywords'))
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

        .campaign-slider {
            margin: 0 -15px;
        }

        .campaign-slider-section {
            padding: 60px 0;
        }

        .campaign-slider {
            margin: 0;
        }

        .campaign-slide {
            background-size: cover;
            background-position: center;
            min-height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 0 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .campaign-slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1;
        }

        .campaign-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            width: 100%;
            padding: 40px;
        }

        .campaign-inner {
            max-width: 600px;
            margin: 0 auto;
        }

        .campaign-slide h2 {
            font-size: 42px;
            margin-bottom: 15px;
            font-weight: 700;
            color: #fff;
        }

        .campaign-slide p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #fff;
            line-height: 1.6;
        }

        .campaign-slide .countdown-timer {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .campaign-slide .cd-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            min-width: 70px;
        }

        .campaign-slide .cd-item span {
            display: block;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .campaign-slide .cd-item p {
            font-size: 11px;
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .campaign-slide .primary-btn {
            padding: 12px 35px;
            font-size: 16px;
            font-weight: 600;
        }

        /* Ensure countdown timer is visible */
        .cd-item span, .cd-item p {
            display: block !important;
            opacity: 1 !important;
        }

        @media (max-width: 991px) {
            .campaign-slide {
                min-height: 380px;
            }

            .campaign-slide h2 {
                font-size: 32px;
            }

            .campaign-slide p {
                font-size: 16px;
                margin-bottom: 20px;
            }

            .campaign-slide .countdown-timer {
                gap: 10px;
            }

            .campaign-slide .cd-item {
                padding: 12px 15px;
                min-width: 60px;
            }

            .campaign-slide .cd-item span {
                font-size: 22px;
            }

            .campaign-slide .cd-item p {
                font-size: 10px;
            }
        }

        @media (max-width: 768px) {
            .campaign-slider-section {
                padding: 40px 0;
            }

            .campaign-slide {
                min-height: 320px;
                margin: 0 5px;
            }

            .campaign-slide h2 {
                font-size: 24px;
                margin-bottom: 10px;
            }

            .campaign-slide p {
                font-size: 14px;
                margin-bottom: 15px;
            }

            .campaign-content {
                padding: 25px;
            }

            .campaign-slide .countdown-timer {
                gap: 8px;
                margin-bottom: 15px;
            }

            .campaign-slide .cd-item {
                padding: 10px 12px;
                min-width: 50px;
            }

            .campaign-slide .cd-item span {
                font-size: 18px;
            }

            .campaign-slide .cd-item p {
                font-size: 9px;
            }

            .campaign-slide .primary-btn {
                padding: 10px 25px;
                font-size: 14px;
            }
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
                                            @include('frontend.product.partials.product_item', ['product' => $product])
                                        @endforeach
                                    </div>
                                </div>
                                @foreach($featuredCats as $cat)
                                    <div class="tab-pane fade" id="feat-{{ $cat->id }}" role="tabpanel">
                                        <div class="product-slider owl-carousel">
                                            @foreach($featuredProducts->where('category_id', $cat->id) as $product)
                                                @include('frontend.product.partials.product_item', ['product' => $product])
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
                                        @include('frontend.product.partials.product_item', ['product' => $product])
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

            @if($campaigns->count() > 0)
                <!-- Campaign Slider Section Begin -->
                <section class="campaign-slider-section spad">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="campaign-carousel owl-carousel">
                                    @foreach ($campaigns as $campaign)
                                        @php
                                            $products = $campaign->campaignProducts->map->product->filter();
                                            $firstProduct = $products->first();
                                            $bgImage = $firstProduct && $firstProduct->thumbnail ? asset($firstProduct->thumbnail) : asset('frontend/assets/img/time-bg.jpg');
                                            $endDate = $campaign->getCountdownEndDate();
                                        @endphp
                                        <div class="campaign-slide" style="background-image: url('{{ $bgImage }}')">
                                            <div class="campaign-content">
                                                <div class="campaign-inner">
                                                    <h2>{{ $campaign->name }}</h2>
                                                    <p>{{ $campaign->description ?? 'Explore our exclusive collection' }}</p>
                                                    @if($endDate)
                                                        <div class="countdown-timer" id="countdown-{{ $campaign->id }}"
                                                             data-end-date="{{ $endDate }} 23:59:59">
                                                            <div class="cd-item">
                                                                <span class="days">00</span>
                                                                <p>Days</p>
                                                            </div>
                                                            <div class="cd-item">
                                                                <span class="hours">00</span>
                                                                <p>Hrs</p>
                                                            </div>
                                                            <div class="cd-item">
                                                                <span class="minutes">00</span>
                                                                <p>Mins</p>
                                                            </div>
                                                            <div class="cd-item">
                                                                <span class="seconds">00</span>
                                                                <p>Secs</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <a href="{{ route('campaign.show', $campaign->slug) }}" class="primary-btn">Shop Campaign</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Campaign Slider Section End -->
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
                                    @include('frontend.product.partials.product_item', ['product' => $product])
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
                                    <div class="blog-thumb-container">
                                        <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}" class="blog-thumbnail">
                                        @if($blog->category)
                                            <div class="blog-category-badge primary-btn">
                                                {{ $blog->category->name }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="latest-text">
                                        <div class="tag-list">
                                            <div class="tag-item">
                                                <i class="fa fa-calendar-o"></i>
                                                {{ $blog->created_date }}
                                            </div>
                                        </div>
                                        <a href="{{ route('blog.details', $blog->slug) }}">
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
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Simple Countdown Timer Function (no jQuery.countdown dependency)
            function updateCountdown($timer) {
                const endDateStr = $timer.data('end-date');
                if (!endDateStr) return;

                const endDate = new Date(endDateStr);
                const now = new Date();
                const diff = endDate - now;

                if (diff <= 0) {
                    $timer.find('.days').text('00');
                    $timer.find('.hours').text('00');
                    $timer.find('.minutes').text('00');
                    $timer.find('.seconds').text('00');
                    $timer.find('.countdown-text').remove();
                    $timer.before('<div class="countdown-expired">Expired</div>');
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                $timer.find('.days').text(days.toString().padStart(2, '0'));
                $timer.find('.hours').text(hours.toString().padStart(2, '0'));
                $timer.find('.minutes').text(minutes.toString().padStart(2, '0'));
                $timer.find('.seconds').text(seconds.toString().padStart(2, '0'));
            }

            function initializeAllCountdowns() {
                $('.countdown-timer').each(function() {
                    const $timer = $(this);
                    if ($timer.data('end-date')) {
                        updateCountdown($timer);
                    }
                });
            }

            // Set up interval for countdown updates
            setInterval(initializeAllCountdowns, 1000);

            // Initialize all countdowns on page load
            initializeAllCountdowns();

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

            /*------------------
                Hero Slider
            --------------------*/
            $(".hero-items").owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                items: 1,
                dots: false,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
                smartSpeed: 1200,
                autoHeight: false,
                autoplay: true,
            });

            /*------------------
                Campaign Carousel - Initialize First
            --------------------*/
            var campaignCarousel = $(".campaign-carousel").owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                onInitialized: function(event) {
                    // Initialize countdowns after carousel loads
                    setTimeout(initializeAllCountdowns, 500);
                },
                onTranslated: function(event) {
                    // Reinitialize countdowns when slide changes
                    initializeAllCountdowns();
                },
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            });

            /*------------------
                Product Slider
            --------------------*/
            $(".product-slider").owlCarousel({
                loop: true,
                margin: 25,
                nav: true,
                items: 4,
                dots: true,
                navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
                smartSpeed: 1200,
                autoHeight: false,
                autoplay: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    576: {
                        items: 2,
                    },
                    992: {
                        items: 2,
                    },
                    1200: {
                        items: 3,
                    }
                }
            });

            /*------------------
                Category Product Slider
            --------------------*/
            $(".category-product-slider").owlCarousel({
                loop: true,
                margin: 25,
                nav: true,
                items: 4,
                dots: true,
                navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
                smartSpeed: 1200,
                autoHeight: false,
                autoplay: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    576: {
                        items: 2,
                    },
                    992: {
                        items: 2,
                    },
                    1200: {
                        items: 4,
                    }
                }
            });

            /*------------------
                Logo Carousel
            --------------------*/
            $(".logo-carousel").owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                items: 5,
                dots: false,
                autoplay: true,
                responsive: {
                    0: {
                        items: 2,
                    },
                    576: {
                        items: 3,
                    },
                    992: {
                        items: 4,
                    },
                    1200: {
                        items: 5,
                    }
                }
            });

            // Additional initialization after page fully loads
            $(window).on('load', function() {
                setTimeout(initializeAllCountdowns, 1000);
            });
        });
    </script>
@endpush
