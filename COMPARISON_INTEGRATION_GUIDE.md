# Product Comparison Integration Guide

This guide explains how to integrate the product comparison functionality into different parts of your Fionas-Style application.

## Quick Start

### 1. Add JavaScript to Your Master Template

In your main layout file (`resources/views/frontend/master.blade.php`), add the comparison module in the footer scripts section:

```blade
@push('scripts')
    <script src="{{ asset('frontend/js/product-comparison.js') }}"></script>
@endpush
```

### 2. Add Comparison Link to Navigation

In your header/navigation (`resources/views/frontend/include/header.blade.php` or similar):

```blade
<!-- In your navigation area -->
<li>
    @include('frontend.compare.badge')
</li>
```

Or with custom styling:

```blade
<a href="{{ route('compare.index') }}" class="header-link">
    <i class="fa fa-exchange"></i>
    Compare
    <?php $compareService = app(\App\Services\Frontend\CompareService::class); ?>
    @if ($compareService->getComparisonCount() > 0)
        <span class="badge">{{ $compareService->getComparisonCount() }}</span>
    @endif
</a>
```

---

## Integration Examples

### 3. Add Compare Button to Product Cards

#### In Shop/Category Listing Page

In your product card template:

```blade
<!-- Product Card -->
<div class="product-card">
    <div class="product-image">
        <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}">
    </div>

    <h4>{{ $product->name }}</h4>
    <p class="price">${{ $product->final_price }}</p>

    <!-- Action Buttons -->
    <div class="product-actions">
        <button class="btn-add-to-cart" onclick="addToCart('{{ $product->slug }}')">
            <i class="fa fa-shopping-cart"></i> Add to Cart
        </button>

        <!-- Compare Button -->
        <button class="btn-compare-product" data-product-id="{{ $product->id }}" title="Add to Comparison">
            <i class="fa fa-exchange"></i> Compare
        </button>
    </div>
</div>
```

Or use the dedicated component:

```blade
@include('frontend.compare.compare-button', ['product' => $product])
```

### 4. Add Comparison Info to Product Detail Page

In your product detail view (`resources/views/frontend/product/show.blade.php`):

```blade
@section('content')
    <div class="product-detail">
        <!-- Product Images, Name, Price etc -->

        <!-- Comparison Info Section -->
        @include('frontend.compare.product-info', ['product' => $product])

        <!-- Add to Cart Button -->
        <button class="btn-add-to-cart">Add to Cart</button>
    </div>
@endsection
```

### 5. Using JavaScript API

The `ProductComparison` module provides a JavaScript API for custom implementations:

#### Add to Comparison

```javascript
ProductComparison.addProduct(123, function (response) {
    if (response.success) {
        console.log("Product added:", response.product);
        console.log("Current count:", response.count);
    } else {
        ProductComparison.notify(response.message, "error");
    }
});
```

#### Remove from Comparison

```javascript
ProductComparison.removeProduct(123, function (response) {
    if (response.success) {
        console.log("Product removed");
        console.log("Current count:", response.count);
    }
});
```

#### Toggle Product (Add or Remove)

```javascript
ProductComparison.toggleProduct(123, function (response) {
    if (response.success) {
        ProductComparison.notify(response.message, "success");
    }
});
```

#### Check if Product is in Comparison

```javascript
ProductComparison.isInComparison(123, function (response) {
    if (response.isInComparison) {
        console.log("Product is being compared");
        document.getElementById("compare-btn").textContent =
            "Remove from Comparison";
    } else {
        console.log("Product is not being compared");
        document.getElementById("compare-btn").textContent =
            "Add to Comparison";
    }
});
```

#### Get Comparison Count

```javascript
ProductComparison.getCount(function (response) {
    console.log("Compared products:", response.count);
    console.log("Maximum:", response.max);

    // Update UI
    document.querySelector(".comparison-count").textContent = response.count;
});
```

#### Clear All Comparisons

```javascript
if (confirm("Clear all comparisons?")) {
    ProductComparison.clearAll(function (response) {
        if (response.success) {
            ProductComparison.notify("Comparison cleared", "success");
            location.reload();
        }
    });
}
```

#### Show Notification

```javascript
ProductComparison.notify("Product added to comparison", "success");
ProductComparison.notify("Error occurred", "error", 5000);
ProductComparison.notify("Please select a variant", "warning");
```

---

## Advanced Integration

### 6. Custom Button Styling

Create your own styled compare button:

```blade
<button id="custom-compare-btn"
        class="my-custom-btn"
        data-product-id="{{ $product->id }}"
        onclick="handleCompareClick(this)">
    <i class="icon-compare"></i> Compare this product
</button>

<script>
    function handleCompareClick(button) {
        const productId = button.getAttribute('data-product-id');

        ProductComparison.toggleProduct(productId, function(response) {
            if (response.success) {
                // Update button appearance
                if (response.message.includes('added')) {
                    button.classList.add('active');
                    button.textContent = '✓ In Comparison';
                } else {
                    button.classList.remove('active');
                    button.textContent = 'Compare';
                }

                // Show notification
                ProductComparison.notify(response.message, 'success');
            } else {
                ProductComparison.notify(response.message, 'error');
            }
        });
    }
</script>

<style>
    .my-custom-btn {
        /* Your custom styles */
    }

    .my-custom-btn.active {
        background: #27ae60;
        border-color: #27ae60;
    }
</style>
```

### 7. Controller Integration

In your controller, you can check comparison status:

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CompareService;
use App\Models\Admin\Product;

class ProductController extends Controller
{
    protected $compareService;

    public function __construct(CompareService $compareService)
    {
        $this->compareService = $compareService;
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('frontend.product.show', [
            'product' => $product,
            'isComparing' => $this->compareService->isProductInComparison($product->id),
            'comparisonCount' => $this->compareService->getComparisonCount(),
        ]);
    }

    public function index()
    {
        $products = Product::paginate(12);

        // For each product, check if it's in comparison
        $comparedIds = $this->compareService->getComparedProductIds();

        return view('frontend.shop.index', [
            'products' => $products,
            'comparedIds' => $comparedIds,
        ]);
    }
}
```

Then in your view:

```blade
@foreach ($products as $product)
    <div class="product-card @if(in_array($product->id, $comparedIds)) is-comparing @endif">
        <!-- Product content -->
    </div>
@endforeach
```

### 8. In-Page Comparison Modal/Sidebar

Create a floating comparison widget:

```blade
<!-- In master.blade.php -->
<div id="comparison-sidebar" class="comparison-sidebar">
    <div class="comparison-header">
        <h3>Comparison</h3>
        <span class="comparison-count">0</span>
        <button class="close-sidebar" onclick="toggleComparisonSidebar()">×</button>
    </div>

    <div class="comparison-items" id="comparison-items">
        <p class="empty-message">No products selected for comparison</p>
    </div>

    <a href="{{ route('compare.index') }}" class="view-comparison-btn">
        View Full Comparison
    </a>
</div>

<script>
    function toggleComparisonSidebar() {
        const sidebar = document.getElementById('comparison-sidebar');
        sidebar.classList.toggle('open');
    }

    // Update sidebar when products are added
    const originalNotify = ProductComparison.notify;
    ProductComparison.notify = function(message, type, duration) {
        originalNotify.call(this, message, type, duration);
        updateComparisonSidebar();
    };

    function updateComparisonSidebar() {
        ProductComparison.getCount(function(response) {
            const count = document.querySelector('.comparison-count');
            if (count) count.textContent = response.count;
        });
    }
</script>

<style>
    .comparison-sidebar {
        position: fixed;
        right: -350px;
        top: 0;
        width: 350px;
        height: 100vh;
        background: white;
        transform: translateX(0);
        transition: right 0.3s ease;
        padding: 20px;
        border-left: 1px solid #eee;
        z-index: 999;
        overflow-y: auto;
    }

    .comparison-sidebar.open {
        right: 0;
    }

    /* Additional styling */
</style>
```

---

## Templates Ready to Use

The following templates are provided and ready to include:

1. **Compare Button** (`frontend.compare.compare-button`)
    - Standalone compare button for products
2. **Comparison Badge** (`frontend.compare.badge`)
    - Navigation/header badge showing count
3. **Product Info** (`frontend.compare.product-info`)
    - Detailed comparison info for product detail page
4. **Comparison Page** (`frontend.compare.index`)
    - Full comparison page view

---

## Style Customization

All styles use standard CSS that can be overridden. Key CSS classes:

```css
/* Compare buttons */
.btn-compare-product {
}
.btn-compare-product.is-comparing {
}

/* Comparison page */
.comparison-container {
}
.comparison-table {
}
.attribute-name {
}
.product-image {
}
.product-price {
}

/* Notifications */
.comparison-notification {
}
.comparison-notification.success {
}
.comparison-notification.error {
}

/* Badge */
.comparison-count-badge {
}
```

Add custom CSS to override default styles:

```css
.btn-compare-product {
    background: your-color;
    border-color: your-border-color;
}

.btn-compare-product.is-comparing {
    background: your-active-color;
}
```

---

## Troubleshooting

### Compare button not working

- Make sure jQuery is loaded before the comparison module
- Check that CSRF token is available in page
- Open browser console for error messages

### Session not persisting

- Verify Laravel sessions are configured correctly
- Check that `config/session.php` is properly set
- Ensure cookies are enabled in browser

### Styling issues

- Check for CSS conflicts with your theme
- Use browser inspector to debug styles
- Ensure Bootstrap/CSS framework is loaded if needed

### Comparison page showing empty

- Clear browser cache and session
- Check that products exist in database
- Verify product IDs are valid in session

---

## Performance Tips

1. **Cache Product Data**: The service uses product caching for better performance
2. **Limit Comparisons**: Default limit is 4 products (configurable)
3. **Session Cleanup**: Sessions are cleaned automatically after inactivity
4. **AJAX Loading**: Use AJAX to avoid full page reloads
5. **Image Optimization**: Ensure product thumbnails are optimized

---

## Support & Customization

For custom modifications:

1. Create custom service extending `CompareService`
2. Create custom controller extending `CompareController`
3. Modify blade templates to match your theme
4. Extend JavaScript module for additional functionality
