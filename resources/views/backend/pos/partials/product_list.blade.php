@forelse($products as $product)
    <div class="product-card" data-json="{{ json_encode([
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->final_price,
            'image_url' => $product->thumbnail ? asset($product->thumbnail) : asset('backend/assets/img/product-placeholder.png'),
            'variants' => $product->variants,
            'stock' => $product->stock_qty ?? 0
        ]) }}">
        <div class="img-container">
            <img src="{{ $product->thumbnail ? asset($product->thumbnail) : asset('backend/assets/img/product-placeholder.png') }}"
                alt="{{ $product->name }}">
            @php
                $stock = $product->stock_qty ?? 0;
                $stockClass = $stock > 10 ? 'bg-success' : ($stock > 0 ? 'bg-warning' : 'bg-danger');
                $stockText = $stock > 0 ? "Stock: {$stock}" : 'Out of Stock';
            @endphp
            <span class="badge {{ $stockClass }} position-absolute top-0 end-0 m-2 rounded-pill shadow-sm">
                {{ $stockText }}
            </span>
        </div>
        <div class="flex-grow-1">
            <h6 class="p-name mb-1">{{ $product->name }}</h6>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <span class="p-price">${{ number_format($product->final_price, 2) }}</span>
                <button class="btn btn-icon btn-round btn-primary btn-xs"><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
@empty
    <div class="grid-full text-center w-100 py-5 opacity-50">
        <i class="fa fa-search fa-3x mb-3"></i>
        <p>No products found</p>
    </div>
@endforelse

@if(isset($products->hasPages) && $products->hasPages())
    <div class="col-12 mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
@endif