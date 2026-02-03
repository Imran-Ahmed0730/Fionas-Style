@extends('frontend.master')
@section('title', $item->name)
@push('css')

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
                            <span>Detail</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb Section Begin -->

        <!-- Product Shop Section Begin -->
        <section class="product-shop spad page-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product-pic-zoom">
                                    <img class="product-big-img" src="{{ asset($item->thumbnail) }}" alt="{{ $item->name }}">
                                    <div class="zoom-icon">
                                        <i class="fa fa-search-plus"></i>
                                    </div>
                                </div>
                                <div class="product-thumbs">
                                    <div class="product-thumbs-track ps-slider owl-carousel">
                                        <div class="pt active" data-imgbigurl="{{ asset($item->thumbnail) }}">
                                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->name }}">
                                        </div>
                                        @if($item->gallery && $item->gallery->count() > 0)
                                            @foreach($item->gallery as $gallery)
                                                <div class="pt" data-imgbigurl="{{ asset($gallery->image) }}">
                                                    <img src="{{ asset($gallery->image) }}" alt="">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="product-details">
                                    <input type="hidden" id="product_id" value="{{$item->id}}">
                                    <input type="hidden" id="variant_id" value="">
                                    <input type="hidden" id="final_price" value="{{$item->final_price}}">
                                    <div class="pd-title">
                                        @if($item->brand_id)
                                            <span>{{ $item->brand->name ?? '' }}</span>
                                        @endif
                                        <h3>{{ $item->name }}</h3>
                                        <a href="#" class="heart-icon"><i class="icon_heart_alt"></i></a>
                                    </div>
                                    <div class="pd-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <span>(5)</span>
                                    </div>
                                    <div class="pd-desc">
                                        <p>{{ $item->short_description ?? '' }}</p>
                                        <h4>৳<span id="pd-final-price">{{ number_format($item->calculated_final_price, 2) }}</span> 
                                                @if($item->calculated_final_price < $item->regular_price)
                                                    <span id="pd-regular-price">৳{{ number_format($item->regular_price, 2) }}</span>
                                                @endif
                                            </h4>
                                        </div>
                                        @if($item->colors && $item->colors->count() > 0)
                                            <div class="pd-color">
                                                <h6>Color</h6>
                                                <div class="pd-color-choose">
                                                    @foreach($item->colors as $color)
                                                        <div class="cc-item">
                                                            <input type="radio" name="color" id="cc-{{ $color->id }}" 
                                                                value="{{ $color->name }}" {{ $loop->first ? 'checked' : '' }}>
                                                            <label for="cc-{{ $color->id }}" 
                                                                style="background-color: {{ $color->code ?: $color->name }};"
                                                                title="{{ $color->name }}"></label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if($item->attributes_with_values && $item->attributes_with_values->count() > 0)
                                            @foreach($item->attributes_with_values as $attribute)
                                                <div class="pd-size-choose" data-attribute-name="{{ $attribute->name }}">
                                                    <h6>{{ $attribute->name }}</h6>
                                                    @foreach($attribute->values as $value)
                                                        <div class="sc-item">
                                                            <input type="radio" name="attribute[{{ $attribute->name }}]" 
                                                                id="attr-{{ $attribute->id }}-{{ $loop->index }}" 
                                                                value="{{ $value }}" {{ $loop->first ? 'checked' : '' }}>
                                                            <label for="attr-{{ $attribute->id }}-{{ $loop->index }}" 
                                                                class="{{ $loop->first ? 'active' : '' }}">{{ $value }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <input type="text" value="1" id="product_qty">
                                            </div>
                                            <a href="javascript:void(0)" class="primary-btn pd-cart" id="add-to-cart-btn">Add To Cart</a>
                                        </div>
                                        <ul class="pd-tags">
                                            <li><span>CATEGORY</span>: {{ $item->category->name }}</li>
                                            @if($item->tags)
                                                <li><span>TAGS</span>: {{ $item->tags }}</li>
                                            @endif
                                        </ul>
                                        <div class="pd-share">
                                            <div class="p-code">Sku : {{ $item->sku }}</div>
                                            <div class="pd-social">
                                                <div class="pd-social">
                                                    <a href="javascript:void(0)" onclick="shareProduct('facebook')" title="Share on Facebook">
                                                        <i class="ti-facebook"></i>
                                                    </a>

                                                    <a href="javascript:void(0)" onclick="shareProduct('twitter')" title="Share on Twitter">
                                                        <i class="ti-twitter-alt"></i>
                                                    </a>

                                                    <a href="javascript:void(0)" onclick="shareProduct('linkedin')" title="Share on LinkedIn">
                                                        <i class="ti-linkedin"></i>
                                                    </a>

                                                    <a href="javascript:void(0)" onclick="shareProduct('pinterest')" title="Share on Pinterest">
                                                        <i class="ti-pinterest"></i>
                                                    </a>

                                                    <a href="javascript:void(0)" onclick="shareProduct('whatsapp')" title="Share on WhatsApp">
                                                        <i class="ti-instagram"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-tab">
                                <div class="tab-item">
                                    <ul class="nav" role="tablist">
                                        <li>
                                            <a class="active" data-toggle="tab" href="#tab-1" role="tab">DESCRIPTION</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#tab-2" role="tab">Additional Information</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#tab-3" role="tab">Shipping Information</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-item-content">
                                    <div class="tab-content">
                                        <div class="tab-pane fade-in active" id="tab-1" role="tabpanel">
                                            <div class="product-content">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h5>Introduction</h5>
                                                        {!! $item->detailed_description !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab-2" role="tabpanel">
                                            <div class="specification-table">
                                                {!! $item->additional_info !!}
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab-3" role="tabpanel">
                                            <div class="specification-table">
                                                {!! $item->shipping_info !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Product Shop Section End -->

            <!-- Related Products Section End -->
            <div class="related-products spad">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title">
                                <h2>Related Products</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @forelse($related_products as $related)
                            <div class="col-lg-3 col-sm-6">
                                @include('frontend.product.partials.product_item', ['product' => $related])
                            </div>
                        @empty
                            <div class="col-lg-12">
                                <div class="section-title">
                                    <p class="text-center text-secondary">No Related Products Found</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {

            // Initial call if variants exist
            if ($('input[name="color"]').length > 0 || $('input[name^="attribute"]').length > 0) {
                updateVariant();
            }

            // Listen for changes
            $('input[name="color"], input[name^="attribute"]').on('change', function () {
                if ($(this).attr('name') === 'color') {
                    $('.pd-color-choose label').removeClass('active');
                    $(this).next('label').addClass('active');
                } else {
                    $(this).closest('.pd-size-choose').find('label').removeClass('active');
                    $(this).next('label').addClass('active');
                }
                updateVariant();
            });

            function updateVariant() {
                let colorName = $('input[name="color"]:checked').val();
                let attributes = {};

                $('input[name^="attribute"]:checked').each(function () {
                    let fullName = $(this).attr('name');
                    let match = fullName.match(/\[(.*?)\]/);
                    if (match) {
                        attributes[match[1]] = $(this).val();
                    }
                });

                $.ajax({
                    url: "{{ route('product.getVariant') }}",
                    method: 'GET',
                    data: {
                        product_id: "{{ $item->id }}",
                        color_name: colorName || null,
                        specs: attributes
                    },
                    success: function (response) {
                        if (response.success && response.variant) {
                            $('#variant_id').val(response.variant.id);
                            $('#pd-final-price').text(parseFloat(response.variant.price).toFixed(2));

                            let regularPrice = parseFloat(response.variant.regular_price);
                            let finalPrice = parseFloat(response.variant.price);
                            if (regularPrice > finalPrice) {
                                if ($('#pd-regular-price').length === 0) {
                                    $('#pd-final-price').after(' <span id="pd-regular-price">৳' + regularPrice.toFixed(2) + '</span>');
                                } else {
                                    $('#pd-regular-price').text('৳' + regularPrice.toFixed(2)).show();
                                }
                            } else {
                                $('#pd-regular-price').hide();
                            }

                            if (response.variant.image) {
                                $('.product-big-img').attr('src', response.variant.image);
                            }

                            if (parseInt(response.variant.stock) > 0) {
                                $('#add-to-cart-btn').removeClass('disabled').prop('disabled', false).css('opacity', 1);
                            } else {
                                $('#add-to-cart-btn').addClass('disabled').prop('disabled', true).css('opacity', 0.5);
                            }
                        } else {
                            $('#add-to-cart-btn').addClass('disabled').prop('disabled', true).css('opacity', 0.5);
                        }
                    }
                });
            }

            // Add to Cart
            $('#add-to-cart-btn').on('click', function (e) {
                e.preventDefault();

                if ($(this).hasClass('disabled')) {
                    toastr.warning('Please select a valid variant or item out of stock');
                    return;
                }

                let slug = "{{ $item->slug }}";
                let quantity = $('#product_qty').val();
                let variantId = $('#variant_id').val();

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "GET",
                    data: {
                        slug: slug,
                        quantity: quantity,
                        variantId: variantId
                    },
                    success: function (response) {
                        if (response.error) {
                            toastr.error(response.error);
                        } else {
                            toastr.success('Product added to cart!');
                            $('.cart-count').text(response.total_quantity);
                        }
                    },
                    error: function (xhr) {
                        toastr.error('Failed to add product');
                    }
                });
            });
        });

        function shareProduct(platform) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            let shareUrl = '';
            switch (platform) {
                case 'facebook': shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`; break;
                case 'twitter': shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`; break;
                case 'linkedin': shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`; break;
                case 'pinterest': shareUrl = `https://pinterest.com/pin/create/button/?url=${url}&description=${title}`; break;
                case 'whatsapp': shareUrl = `https://wa.me/?text=${title}%20${url}`; break;
            }
            window.open(shareUrl, 'shareWindow', 'width=600,height=500,scrollbars=yes');
        }
    </script>
@endpush