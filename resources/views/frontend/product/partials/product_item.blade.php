<div class="product-item">
    <div class="pi-pic">
        <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->name }}">
        @if($product->final_price < $product->regular_price)
            <div class="sale">Sale</div>
        @endif
        <div class="icon add-to-wishlist" data-id="{{ $product->id }}" style="cursor: pointer;"><i
                class="icon_heart_alt"></i></div>
        <ul>
            <li class="w-icon active"><a href="javascript:void(0)"
                    class="{{ $product->variants->isNotEmpty() ? 'btn-quick-view' : 'add-to-cart' }}"
                    data-slug="{{ $product->slug }}" data-id="{{ $product->id }}"><i class="icon_bag_alt"></i></a></li>
            <li class="quick-view">
                <a href="javascript:void(0)" class="btn-quick-view" data-id="{{ $product->id }}">
                    + Quick View
                </a>
            </li>

            <li class="w-icon compare-icon" style="cursor: pointer;" data-id="{{ $product->id }}" title="Add to Comparison">
                <a href="javascript:void(0)" class="btn-compare-product-item" data-product-id="{{ $product->id }}">
                    <i class="ti-loop"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="pi-text">
        <div class="catagory-name">{{ $product->category->name }}</div>
        <a href="{{ route('product.show', $product->slug) }}">
            <h5>{{ Str::limit($product->name, 20, '...') }}</h5>
        </a>
        <div class="product-price">
            ৳{{ number_format($product->final_price, 2) }}
            @if($product->final_price < $product->regular_price)
                <span>৳{{ number_format($product->regular_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>
