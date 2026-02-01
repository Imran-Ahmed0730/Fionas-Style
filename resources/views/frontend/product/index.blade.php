@extends('frontend.master')
@section('title', 'Shop')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Shop</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Product Shop Section Begin -->
    <section class="product-shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter">
                    //TODO make this section schrollable and implement caching

                    <form action="{{ route('shop') }}" method="GET" id="filterForm">
                        {{-- Categories --}}
                        <div class="filter-widget">
                            <h4 class="fw-title">Categories</h4>
                            <ul class="filter-catagories">
                                @foreach($sidebar['categories'] as $category)
                                    <li>
                                        <a href="{{ route('shop', array_merge(request()->all(), ['category' => $category->slug])) }}" 
                                           class="{{ request('category') == $category->slug ? 'text-primary' : '' }}">
                                            {{ $category->name }} ({{ $category->products_count }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Brands --}}
                        <div class="filter-widget">
                            <h4 class="fw-title">Brand</h4>
                            <div class="fw-brand-check">
                                @foreach($sidebar['brands'] as $brand)
                                    <div class="bc-item">
                                        <label for="bc-{{ $brand->id }}">
                                            {{ $brand->name }}
                                            <input type="checkbox" id="bc-{{ $brand->id }}" name="brand[]" value="{{ $brand->slug }}"
                                                {{ in_array($brand->slug, (array) request('brand', [])) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="filter-widget">
                            <h4 class="fw-title">Price</h4>
                            <div class="filter-range-wrap">
                                <div class="range-slider">
                                    <div class="price-input">
                                        <input type="text" id="minamount" name="min_price" value="{{ request('min_price', 0) }}">
                                        <input type="text" id="maxamount" name="max_price" value="{{ request('max_price', 1000) }}">
                                    </div>
                                </div>
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                    data-min="0" 
                                    data-max="50000"
                                    data-start-min="{{ request('min_price', 0) }}"
                                    data-start-max="{{ request('max_price', 50000) }}">
                                    <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                </div>
                            </div>
                            <button type="submit" class="filter-btn">Filter</button>
                        </div>

                        {{-- Sorting (Hidden fields to persist sort) --}}
                        @if(request('sort'))
                             <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                    </form>
                </div>

                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="product-show-option">
                        <div class="row">
                            <div class="col-lg-7 col-md-7">
                                <div class="select-option">
                                    <select class="sorting" onchange="window.location.href = this.value;">
                                        <option value="{{ route('shop', array_merge(request()->all(), ['sort' => 'latest'])) }}" {{ request('sort') == 'latest' ? 'selected' : '' }}>Default Sorting (Latest)</option>
                                        <option value="{{ route('shop', array_merge(request()->all(), ['sort' => 'price_asc'])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="{{ route('shop', array_merge(request()->all(), ['sort' => 'price_desc'])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 text-right">
                                <p>Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} Of {{ $items->total() }} Product</p>
                            </div>
                        </div>
                    </div>

                    <div class="product-list">
                        <div class="row">
                            @forelse($items as $product)
                                <div class="col-lg-4 col-sm-6">
                                    @include('frontend.product.partials.product_item', ['product' => $product])
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">No products found.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="loading-more">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Shop Section End -->
@endsection