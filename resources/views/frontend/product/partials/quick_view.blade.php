@php
    $currency_symbol = '৳';
@endphp

<style>
    .quick-view-wrapper {
        padding: 15px;
    }

    .qv-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background: #f8f8f8;
    }

    .qv-image-container img {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }

    .qv-image-container:hover img {
        transform: scale(1.05);
    }

    .quick-view-info {
        padding-left: 20px;
    }

    .quick-view-info .category {
        color: #e7ab3c;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 5px;
    }

    .quick-view-info h2 {
        color: #252525;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 24px;
    }

    .qv-price-wrapper {
        margin-bottom: 15px;
    }

    .qv-price {
        color: #e7ab3c;
        font-size: 24px;
        font-weight: 700;
    }

    .qv-old-price {
        color: #b2b2b2;
        text-decoration: line-through;
        font-size: 18px;
        margin-left: 10px;
        font-weight: 400;
    }

    .qv-description {
        color: #636363;
        line-height: 26px;
        margin-bottom: 25px;
        font-size: 14px;
    }

    .qv-option-title {
        color: #252525;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 14px;
        display: block;
    }

    .qv-colors {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .qv-color-item {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
        position: relative;
    }

    .qv-color-item.active {
        border-color: #e7ab3c;
        transform: scale(1.1);
    }

    .qv-attributes {
        margin-bottom: 20px;
    }

    .qv-attr-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .qv-attr-item {
        padding: 5px 15px;
        border: 1px solid #ebebeb;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
    }

    .qv-attr-item.active {
        background: #e7ab3c;
        color: #fff;
        border-color: #e7ab3c;
    }

    .qv-action-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 25px;
    }

    .qv-qty-input {
        width: 100px;
        display: flex;
        border: 1px solid #ebebeb;
        border-radius: 4px;
    }

    .qv-qty-btn {
        width: 30px;
        height: 40px;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .qv-qty-input input {
        width: 40px;
        border: none;
        text-align: center;
        font-weight: 700;
    }

    .add-to-cart {
        background: #e7ab3c;
        color: #fff;
        border: none;
        padding: 12px 30px;
        font-weight: 700;
        border-radius: 4px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s;
    }

    .add-to-cart:hover {
        background: #d99a2b;
    }

    .qv-full-details {
        display: block;
        margin-top: 20px;
        color: #252525;
        font-weight: 700;
        text-decoration: underline;
    }

    .qv-stock-info {
        margin-top: 15px;
        font-size: 13px;
    }

    .qv-stock-info.in-stock {
        color: #28a745;
    }

    .qv-stock-info.out-of-stock {
        color: #dc3545;
    }

    @media (max-width: 767px) {
        .quick-view-info {
            padding-left: 0;
            margin-top: 20px;
        }
    }
</style>

<div class="quick-view-wrapper">
    <div class="row">
        <div class="col-md-6">
            <div class="qv-image-container">
                <img id="qv-main-image" src="{{ asset($product->thumbnail) }}" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="quick-view-info product-details">
                <input type="hidden" id="product_id" value="{{$product->id}}">
                <input type="hidden" id="variant_name" value="">
                <input type="hidden" id="variant_attr_name" value="">
                <input type="hidden" id="final_price" value="{{ number_format($product->calculated_final_price, 2) }}">
                <span class="category">{{ $product->category->name }}</span>
                @if($product->brand)
                    <span class="brand text-muted d-block small mb-1">Brand: {{ $product->brand->name }}</span>
                @endif
                <h2>{{ $product->name }}</h2>

                <div class="qv-price-wrapper">
                    <span class="qv-price">{{ $currency_symbol }}<span
                            id="qv-final-price">{{ number_format($product->calculated_final_price, 2) }}</span></span>
                    @if($product->calculated_final_price < $product->regular_price)
                        <span class="qv-old-price">{{ $currency_symbol }}<span
                                id="qv-regular-price">{{ number_format($product->regular_price, 2) }}</span></span>
                    @endif
                </div>

                <div class="qv-description">
                    {!! $product->short_description !!}
                </div>

                <form id="qv-add-to-cart-form">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="qv-variant-id" value="">

                    {{-- Colors --}}
                    @if(isset($product->colors) && $product->colors->count() > 0)
                        <div class="qv-options">
                            <span class="qv-option-title">Color:</span>
                            <div class="qv-colors">
                                @foreach($product->colors as $color)
                                    <div class="qv-color-item {{ $loop->first ? 'active' : '' }}"
                                        data-color-name="{{ $color->name }}"
                                        style="background-color: {{ $color->code ?: $color->name ?: 'transparent' }};"
                                        title="{{ $color->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Attributes --}}
                    @if(isset($product->attributes_with_values) && $product->attributes_with_values->count() > 0)
                        @foreach($product->attributes_with_values as $attribute)
                            <div class="qv-attributes">
                                <span class="qv-option-title">{{ $attribute->name }}:</span>
                                <div class="qv-attr-list" data-attribute-name="{{ $attribute->name }}">
                                    @foreach($attribute->values as $value)
                                        <div class="qv-attr-item {{ $loop->first ? 'active' : '' }}" data-value="{{ $value }}">
                                            {{ $value }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="qv-stock-info {{ $product->total_stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                        <i class="{{ $product->total_stock > 0 ? 'ti-check' : 'ti-close' }}"></i>
                        <span id="qv-stock-text">{{ $product->total_stock > 0 ? 'In Stock' : 'Out of Stock' }}</span>
                    </div>

                    <div class="qv-action-wrapper">
                        <div class="qv-qty-input">
                            <button type="button" class="qv-qty-btn qv-minus">-</button>
                            <input type="number" id="procuctQuantity" class="qty" name="quantity" value="1" min="1"
                                readonly>
                            <button type="button" class="qv-qty-btn qv-plus">+</button>
                        </div>
                        <button type="button" data-slug="{{ $product->slug }}" class="add-to-cart" {{ $product->total_stock > 0 ? '' : 'disabled' }}>
                            Add to Cart
                        </button>
                    </div>
                </form>

                @if($product->tags)
                    <div class="qv-tags mt-3 small">
                        <span class="fw-bold">Tags:</span> {{ $product->tags }}
                    </div>
                @endif

                <a href="{{ route('product.show', $product->slug) }}" class="qv-full-details">View Full Details</a>
            </div>
        </div>
    </div>
</div>

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
                        let price = parseFloat(response.variant.price);
                        $('#variant_name').val(response.variant.name);
                        $('#variant_attr_name').val(response.variant.attr_name);
                        $('#final_price').val(price.toFixed(2));
                        $('#qv-variant-id').val(response.variant.id);
                        $('#qv-final-price').text(price.toFixed(2));
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
                            $('.qv-stock-info i').removeClass('ti-close').addClass('ti-check');
                            $('.qv-add-to-cart').prop('disabled', false);
                        } else {
                            $('#qv-stock-text').text('Out of Stock');
                            $('.qv-stock-info').removeClass('in-stock').addClass('out-of-stock');
                            $('.qv-stock-info i').removeClass('ti-check').addClass('ti-close');
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