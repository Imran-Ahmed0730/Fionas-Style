@extends('frontend.master')
@section('title', 'All Collections')
@push('css')
    <style>
        .all-categories-section {
            background-color: #ffffff;
            overflow: hidden;
        }

        .category-row {
            padding: 40px 0;
            position: relative;
        }

        .category-row:nth-child(even) .row {
            flex-direction: row-reverse;
        }

        /* Soft background sequence */
        .category-row:nth-child(5n+1) { background-color: #fcf6f6; }
        .category-row:nth-child(5n+2) { background-color: #f7f9fc; }
        .category-row:nth-child(5n+3) { background-color: #fdfaf5; }
        .category-row:nth-child(5n+4) { background-color: #f6fcf8; }
        .category-row:nth-child(5n+5) { background-color: #f9f7fc; }

        .cat-info-side {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cat-info-side h2 {
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 800;
            color: #252525;
            margin-bottom: 10px;
            line-height: 1.1;
        }

        .cat-info-side p {
            font-size: 15px;
            color: #636363;
            margin-bottom: 15px;
            max-width: 450px;
            line-height: 1.5;
        }

        .shop-all-btn {
            display: inline-flex;
            align-items: center;
            font-size: 13px;
            font-weight: 700;
            color: #252525;
            text-transform: uppercase;
            border-bottom: 2px solid #252525;
            padding-bottom: 3px;
            letter-spacing: 1px;
            margin-bottom: 25px;
            transition: 0.3s;
            align-self: flex-start;
        }

        .shop-all-btn:hover {
            color: #e7ab3c;
            border-color: #e7ab3c;
            text-decoration: none;
            padding-left: 5px;
        }

        /* Subcategory Slider Styles */
        .sub-slider-wrap {
            max-width: 100%;
            position: relative;
        }

        .sub-thumb-item {
            background: #fff;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            transition: 0.3s;
            border: 1px solid transparent;
            margin: 10px 5px;
            display: block;
        }

        .sub-thumb-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #e7ab3c;
            text-decoration: none;
        }

        .sub-thumb-item .thumb-img {
            width: 100%;
            height: 100px;
            overflow: hidden;
            border-radius: 2px;
            margin-bottom: 10px;
            background-color: #f8f8f8;
        }

        .sub-thumb-item .thumb-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sub-thumb-item span {
            font-size: 12px;
            font-weight: 700;
            color: #252525;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Large Image Side */
        .cat-image-side {
            position: relative;
            height: 385px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .cat-image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.6s ease;
        }

        .category-row:hover .cat-image-side img {
            transform: scale(1.05);
        }

        .cat-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        /* Department Section */
        .department-section {
            padding: 80px 0;
            background-color: #fff;
            border-top: 1px solid #eee;
        }

        .dept-item {
            text-align: center;
            margin-bottom: 30px;
            display: block;
        }

        .dept-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            border: 4px solid #fff;
        }

        .dept-item:hover .dept-img {
            transform: scale(1.05);
            border-color: #e7ab3c;
            box-shadow: 0 10px 25px rgba(231, 171, 60, 0.2);
        }

        .dept-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dept-item h5 {
            font-weight: 700;
            color: #252525;
            font-size: 16px;
            transition: 0.3s;
        }

        .dept-item:hover h5 {
            color: #e7ab3c;
        }

        /* Custom Slider Nav */
        .sub-slider.owl-carousel .owl-nav button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            background: #fff !important;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            font-size: 14px !important;
            color: #252525 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: 0.3s;
        }

        .sub-slider-wrap:hover .owl-nav button {
            opacity: 1;
        }

        .sub-slider.owl-carousel .owl-nav .owl-prev { left: -15px; }
        .sub-slider.owl-carousel .owl-nav .owl-next { right: -15px; }

        @media (max-width: 991px) {
            .category-row { padding: 40px 0; }
            .cat-info-side { padding: 20px 15px; text-align: center; }
            .shop-all-btn { align-self: center; }
            .category-row:nth-child(even) .row { flex-direction: column-reverse; }
            .cat-image-side { height: 280px; margin-bottom: 20px; }
            .cat-info-side p { margin-left: auto; margin-right: auto; }
        }

        @media (max-width: 575px) {
            .cat-image-side { height: 210px; }
            .cat-info-side h2 { font-size: 26px; }
            .dept-img { width: 100px; height: 100px; }
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
                        <span>Collections</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Categories Sections -->
    <div class="all-categories-section">
        @foreach($categories as $category)
            <section class="category-row">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Content Side -->
                        <div class="col-lg-6 cat-info-side">
                            <h2>{{ $category->name }}</h2>
                            @if($category->description)
                                <p>{{ Str::limit($category->description, 100) }}</p>
                            @else
                                <p>Discover our exclusive selection of premium {{ strtolower($category->name) }} products crafted for style and comfort.</p>
                            @endif
                            <a href="{{ route('category', $category->slug) }}" class="shop-all-btn">Show all {{ strtolower($category->name) }}</a>

                            <!-- Subcategory Slider -->
                            @if($category->subcategories->count() > 0)
                                <div class="sub-slider-wrap">
                                    <div class="sub-slider owl-carousel">
                                        @foreach($category->subcategories as $sub)
                                            <a href="{{ route('category', $sub->slug) }}" class="sub-thumb-item">
                                                <div class="thumb-img">
                                                    @if($sub->cover_photo && file_exists(public_path($sub->cover_photo)))
                                                        <img src="{{ asset($sub->cover_photo) }}" alt="{{ $sub->name }}">
                                                    @else
                                                        <img src="{{ asset('backend/assets/img/default-150x150.png') }}" alt="{{ $sub->name }}">
                                                    @endif
                                                </div>
                                                <span>{{ $sub->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Image Side -->
                        <div class="col-lg-6">
                            <div class="cat-image-side">
                                @if($category->cover_photo && file_exists(public_path($category->cover_photo)))
                                    <img src="{{ asset($category->cover_photo) }}" alt="{{ $category->name }}">
                                @else
                                    <img src="{{ asset('backend/assets/img/default-150x150.png') }}" alt="{{ $category->name }}">
                                @endif
                                <div class="cat-overlay"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    </div>

    <!-- Departments Circular Grid -->
    <section class="department-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center mb-5">
                    <div class="section-title">
                        <h2>Explore Featured Categories</h2>
                    </div>
                </div>
                @foreach($categories->where('is_featured', 1) as $category)
                    <div class="col-lg-2 col-md-3 col-6">
                        <a href="{{ route('category', $category->slug) }}" class="dept-item">
                            <div class="dept-img">
                                @if($category->cover_photo && file_exists(public_path($category->cover_photo)))
                                    <img src="{{ asset($category->cover_photo) }}" alt="{{ $category->name }}">
                                @else
                                    <img src="{{ asset('backend/assets/img/default-150x150.png') }}" alt="{{ $category->name }}">
                                @endif
                            </div>
                            <h5>{{ $category->name }}</h5>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trust Features -->
    <div class="container spad">
        <div class="row border-top pt-5 text-center">
            <div class="col-lg-4">
                <div class="benefit-item">
                    <div class="fa-icon"><i class="fa fa-shopping-bag" style="color: #e7ab3c; font-size: 30px;"></i></div>
                    <div class="benefit-text">
                        <h6>Premium Quality</h6>
                        <p>We source only the best materials for our collections.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="benefit-item">
                    <div class="fa-icon"><i class="fa fa-truck" style="color: #e7ab3c; font-size: 30px;"></i></div>
                    <div class="benefit-text">
                        <h6>Nationwide Shipping</h6>
                        <p>Fast and secure delivery to your doorstep.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="benefit-item">
                    <div class="fa-icon"><i class="fa fa-headphones" style="color: #e7ab3c; font-size: 30px;"></i></div>
                    <div class="benefit-text">
                        <h6>Expert Assistance</h6>
                        <p>Our team is available 24/7 for your shopping needs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.sub-slider').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                responsive: {
                    0: { items: 2 },
                    480: { items: 3 },
                    768: { items: 3 },
                    992: { items: 3 },
                    1200: { items: 3 }
                }
            });
        });
    </script>
@endpush