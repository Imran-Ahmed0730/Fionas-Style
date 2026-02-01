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
                        <a href="">Shop</a>
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
                                <input type="hidden" id="variant_name" value="">
                                <input type="hidden" id="variant_attr_name" value="">
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
                                        <a href="javascript:void(0)" data-slug="{{ $item->slug }}" class="primary-btn pd-cart add-to-cart" id="add-to-cart-btn">Add To Cart</a>
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
                                    <!-- <li>
                                        <a data-toggle="tab" href="#tab-4" role="tab">Customer Reviews (02)</a>
                                    </li> -->
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
                                    //TODO Include review module
                                    <!-- <div class="tab-pane fade" id="tab-4" role="tabpanel">
                                        <div class="customer-review-option">
                                            <h4>2 Comments</h4>
                                            <div class="comment-option">
                                                <div class="co-item">
                                                    <div class="avatar-pic">
                                                        <img src="{{ asset('frontend') }}/assets/img/product-single/avatar-1.png" alt="">
                                                    </div>
                                                    <div class="avatar-text">
                                                        <div class="at-rating">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star-o"></i>
                                                        </div>
                                                        <h5>Brandon Kelley <span>27 Aug 2019</span></h5>
                                                        <div class="at-reply">Nice !</div>
                                                    </div>
                                                </div>
                                                <div class="co-item">
                                                    <div class="avatar-pic">
                                                        <img src="{{ asset('frontend') }}/assets/img/product-single/avatar-2.png" alt="">
                                                    </div>
                                                    <div class="avatar-text">
                                                        <div class="at-rating">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star-o"></i>
                                                        </div>
                                                        <h5>Roy Banks <span>27 Aug 2019</span></h5>
                                                        <div class="at-reply">Nice !</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="personal-rating">
                                                <h6>Your Ratind</h6>
                                                <div class="rating">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                            </div>
                                            <div class="leave-comment">
                                                <h4>Leave A Comment</h4>
                                                <form action="#" class="comment-form">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <input type="text" placeholder="Name">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" placeholder="Email">
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <textarea placeholder="Messages"></textarea>
                                                            <button type="submit" class="site-btn">Send message</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> -->
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

        <!-- Related Products Section End -->
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            console.log('Product Details JS Loaded');

            // Initial call if variants exist
            if ($('input[name="color"]').length > 0 || $('input[name^="attribute"]').length > 0) {
                updateVariant();
            }

            // Listen for changes
            $('input[name="color"], input[name^="attribute"]').on('change', function () {
                // Update active state of labels
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

                // Collect attributes in order
                let attributeArray = [];

                $('input[name^="attribute"]:checked').each(function () {
                    let value = $(this).val();
                    if (value) {
                        attributeArray.push(value.trim());
                    }
                });

                // Convert array to object for backend
                $('input[name^="attribute"]:checked').each(function () {
                    let fullName = $(this).attr('name');
                    let match = fullName.match(/\[(.*?)\]/);
                    if (match) {
                        let attrName = match[1];
                        attributes[attrName] = $(this).val();
                    }
                });

                console.log('Sending AJAX Request:', {
                    color_name: colorName || null,
                    attributes: attributes,
                    product_id: "{{ $item->id }}"
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
                        console.log('Variant Response:', response);

                        if (response.success && response.variant) {
                            // Update price
                            $('#pd-final-price').text(parseFloat(response.variant.price).toFixed(2));

                            // Update regular price if different
                            let regularPrice = parseFloat(response.variant.regular_price);
                            let finalPrice = parseFloat(response.variant.price);

                            $('#variant_name').val(response.variant.name);
                            $('#variant_attr_name').val(response.variant.attr_name);
                            $('#final_price').val(finalPrice.toFixed(2));

                            if (regularPrice > finalPrice) {
                                if ($('#pd-regular-price').length === 0) {
                                    $('#pd-final-price').after(' <span id="pd-regular-price">৳' + regularPrice.toFixed(2) + '</span>');
                                } else {
                                    $('#pd-regular-price').text('৳' + regularPrice.toFixed(2)).show();
                                }
                            } else {
                                $('#pd-regular-price').hide();
                            }

                            // Update image if available
                            if (response.variant.image) {
                                $('.product-big-img').attr('src', response.variant.image);

                                // Update zoom image if zoom plugin exists
                                if ($('.zoomImg').length) {
                                    $('.zoomImg').attr('src', response.variant.image);
                                }

                                // Update first thumbnail
                                $('.product-thumbs .pt.active img').attr('src', response.variant.image);
                                $('.product-thumbs .pt.active').attr('data-imgbigurl', response.variant.image);
                            }

                            // Update stock status
                            let stock = parseInt(response.variant.stock) || 0;

                            if (stock > 0) {
                                $('#pd-stock-status').text(stock + ' in stock')
                                    .css('color', '#28a745');

                                $('#add-to-cart-btn')
                                    .removeClass('disabled')
                                    .removeAttr('style')
                                    .prop('disabled', false);
                            } else {
                                $('#pd-stock-status').text('Out of Stock')
                                    .css('color', '#dc3545');

                                $('#add-to-cart-btn')
                                    .addClass('disabled')
                                    .css({
                                        'background-color': '#ccc',
                                        'cursor': 'not-allowed',
                                        'pointer-events': 'none'
                                    })
                                    .prop('disabled', true);
                            }
                        } else {
                            // Variant not found
                            console.warn('Variant not found:', response);

                            $('#pd-stock-status').text('Variant Not Available')
                                .css('color', '#ff6b6b');

                            $('#add-to-cart-btn')
                                .addClass('disabled')
                                .css({
                                    'background-color': '#ccc',
                                    'cursor': 'not-allowed',
                                    'pointer-events': 'none'
                                })
                                .prop('disabled', true);

                            // Show debug info in console
                            if (response.debug) {
                                console.table(response.debug.available_variants);
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', {
                            status: status,
                            error: error,
                            response: xhr.responseJSON
                        });

                        $('#pd-stock-status').text('Error loading variant')
                            .css('color', '#dc3545');
                    }
                });
            }

            // Add to cart with variant validation
            $('#add-to-cart-btn').on('click', function (e) {
                e.preventDefault();

                if ($(this).hasClass('disabled')) {
                    alert('This variant is not available');
                    return false;
                }

                // Your add to cart logic here
                let quantity = parseInt($('#product_qty').val()) || 1;

                console.log('Adding to cart:', {
                    product_id: "{{ $item->id }}",
                    quantity: quantity,
                    color: $('input[name="color"]:checked').val(),
                    attributes: getSelectedAttributes()
                });

                // Implement your add to cart AJAX call here
            });

            function getSelectedAttributes() {
                let attrs = {};
                $('input[name^="attribute"]:checked').each(function () {
                    let fullName = $(this).attr('name');
                    let match = fullName.match(/\[(.*?)\]/);
                    if (match) {
                        attrs[match[1]] = $(this).val();
                    }
                });
                return attrs;
            }
        });
    </script>
    <script>
        function shareProduct(platform) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);

            let shareUrl = '';

            switch (platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;

                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;

                case 'linkedin':
                    shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                    break;

                case 'pinterest':
                    shareUrl = `https://pinterest.com/pin/create/button/?url=${url}&description=${title}`;
                    break;

                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${title}%20${url}`;
                    break;
            }

            window.open(
                shareUrl,
                'shareWindow',
                'width=600,height=500,scrollbars=yes'
            );
        }
    </script>
    <script>
        $(document).ready(function () {
            // Initial variant update if options exist
            if ($('.qv-color-item').length > 0 || $('.qv-attr-item').length > 0) {
                updateVariant();
            }

            // Color Selection
            $('.qv-color-item').on('click', function () {
                $('.qv-color-item').removeClass('active');
                $(this).addClass('active');
                updateVariant();
            });

            // Attribute Selection
            $('.qv-attr-item').on('click', function () {
                $(this).parent().find('.qv-attr-item').removeClass('active');
                $(this).addClass('active');
                updateVariant();
            });

            // Quantity Logic
            $('.qv-minus').on('click', function () {
                let input = $(this).parent().find('input');
                let val = parseInt(input.val());
                if (val > 1) input.val(val - 1);
            });

            $('.qv-plus').on('click', function () {
                let input = $(this).parent().find('input');
                let val = parseInt(input.val());
                input.val(val + 1);
            });

            function updateVariant() {
                let productId = $('input[name="product_id"]').val();
                let colorName = $('.qv-color-item.active').data('color-name');
                let attributes = {};

                $('.qv-attr-item.active').each(function () {
                    let attrName = $(this).closest('.qv-attr-list').data('attribute-name');
                    let attrValue = $(this).data('value');
                    attributes[attrName] = attrValue;
                });

                // If product has options but not all are selected, don't fetch yet
                let hasColors = $('.qv-colors').length > 0;
                let hasAttrs = $('.qv-attributes').length > 0;
                let totalAttrGroups = $('.qv-attributes').length;

                if ((hasColors && !colorName) || (hasAttrs && Object.keys(attributes).length < totalAttrGroups)) {
                    return;
                }

                console.log('QV Request:', { productId, colorName, attributes });
                $.ajax({
                    url: "{{ route('product.getVariant') }}",
                    method: 'GET',
                    data: {
                        product_id: productId,
                        color_name: colorName,
                        specs: attributes
                    },
                    success: function (response) {
                        console.log('QV Variant Response:', response);
                        if (response.success) {
                            $('#qv-variant-id').val(response.variant.id);
                            $('#qv-final-price').text(parseFloat(response.variant.price).toFixed(2));
                            if (parseFloat(response.variant.regular_price) > parseFloat(response.variant.price)) {
                                $('#qv-regular-price').text(parseFloat(response.variant.regular_price).toFixed(2));
                                $('.qv-old-price').show();
                            } else {
                                $('.qv-old-price').hide();
                            }

                            $('#qv-main-image').attr('src', response.variant.image);

                            if (parseInt(response.variant.stock) > 0) {
                                $('#qv-stock-text').text('In Stock');
                                $('.qv-stock-info').removeClass('out-of-stock').addClass('in-stock');
                                $('.qv-stock-info i').removeClass('fa-times-circle').addClass('fa-check-circle');
                                $('.qv-add-to-cart').prop('disabled', false);
                            } else {
                                $('#qv-stock-text').text('Out of Stock');
                                $('.qv-stock-info').removeClass('in-stock').addClass('out-of-stock');
                                $('.qv-stock-info i').removeClass('fa-check-circle').addClass('fa-times-circle');
                                $('.qv-add-to-cart').prop('disabled', true);
                            }
                        } else {
                            // If no variant found for selection, disable add to cart
                            $('#qv-stock-text').text('Option Unavailable');
                            $('.qv-stock-info').removeClass('in-stock').addClass('out-of-stock');
                            $('.qv-add-to-cart').prop('disabled', true);
                        }
                    }
                });
            }
        });
    </script>

@endpush