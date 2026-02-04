@extends('frontend.master')
@section('title', 'Shop')
@section('meta_title', 'Shop')
@section('meta_description', 'Browse our wide range of products.')
@section('meta_keywords', 'shop, products, ecommerce')

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
                @include('frontend.product.partials.sidebar', ['route' => 'shop'])


                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="product-show-option">
                        <div class="row">
                            <div class="col-lg-7 col-md-7">
                                <div class="select-option">
                                    <select class="sorting" onchange="window.location.href = this.value;">
                                        <option
                                            value="{{ route('shop', array_merge(request()->all(), ['sort' => 'latest'])) }}"
                                            {{ request('sort') == 'latest' ? 'selected' : '' }}>Default Sorting (Latest)
                                        </option>
                                        <option
                                            value="{{ route('shop', array_merge(request()->all(), ['sort' => 'price_asc'])) }}"
                                            {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option
                                            value="{{ route('shop', array_merge(request()->all(), ['sort' => 'price_desc'])) }}"
                                            {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 text-right">
                                <p>Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} Of
                                    {{ $items->total() }} Product
                                </p>
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