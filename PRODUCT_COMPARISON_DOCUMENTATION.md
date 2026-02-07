# Product Comparison Feature

This document explains the product comparison functionality implemented in the Fionas-Style application.

## Components

### 1. **CompareService** (`app/Services/Frontend/CompareService.php`)

The service layer that handles all comparison logic:

#### Key Methods:

- **`getComparedProducts()`** - Returns all products currently in comparison
- **`addToComparison($productId)`** - Add a product to comparison (max 4 products)
- **`removeFromComparison($productId)`** - Remove a product from comparison
- **`clearComparison()`** - Clear all products from comparison
- **`getComparisonCount()`** - Get number of products in comparison
- **`isProductInComparison($productId)`** - Check if product is already compared
- **`getComparisonData()`** - Get detailed comparison data with attributes
- **`canAddMore()`** - Check if more products can be added

#### Storage:

- Uses Laravel Sessions to store compared product IDs
- Session key: `product_comparison`
- Maximum 4 products can be compared at once

### 2. **CompareController** (`app/Http/Controllers/Frontend/CompareController.php`)

Handles HTTP requests for comparison operations:

#### Routes:

- `GET /compare` - Display comparison page
- `POST /compare/add` - Add product to comparison (AJAX)
- `POST /compare/remove` - Remove product from comparison (AJAX)
- `POST /compare/clear` - Clear all comparisons (AJAX)
- `GET /compare/count` - Get comparison count (AJAX)
- `POST /compare/is-in-comparison` - Check if product is compared (AJAX)

#### Methods:

```php
// Add to comparison
public function add(Request $request)
```

```php
// Remove from comparison
public function remove(Request $request)
```

```php
// Get comparison count
public function getCount()
```

### 3. **Views**

#### Main Comparison Page (`resources/views/frontend/compare/index.blade.php`)

The main comparison page displays:

- Breadcrumb navigation
- List of compared products with images and prices
- Comparison table with attributes:
    - Brand
    - Category
    - Stock Status
    - Color
    - Weight
    - Dimensions
    - Description
- Actions: Add to Cart, Remove from Comparison, Clear All

#### Compare Button Component (`resources/views/frontend/compare/compare-button.blade.php`)

A reusable button component to add products to comparison. Can be included in product cards:

```blade
@include('frontend.compare.compare-button', ['product' => $product])
```

Features:

- Updates button state when product is added
- Shows success/error notifications
- Updates comparison count badge dynamically

#### Comparison Badge (`resources/views/frontend/compare/badge.blade.php`)

A header component showing the comparison count. Can be included in navigation:

```blade
@include('frontend.compare.badge')
```

Features:

- Shows comparison count
- Links to comparison page
- Animated pulse effect when count updates

## Usage Examples

### 1. In a Product Listing/Card

Add the compare button to your product card view:

```blade
<div class="product-card">
    <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}">
    <h3>{{ $product->name }}</h3>
    <p>${{ $product->final_price }}</p>

    <!-- Add compare button -->
    @include('frontend.compare.compare-button', ['product' => $product])
</div>
```

### 2. In Header/Navigation

Add the comparison badge to your header:

```blade
<nav class="header-nav">
    <!-- Other nav items -->
    @include('frontend.compare.badge')
</nav>
```

### 3. Add Product via JavaScript

```javascript
// Add to comparison via AJAX
$.post(
    '{{ route("compare.add") }}',
    {
        product_id: 123,
        _token: "{{ csrf_token() }}",
    },
    function (response) {
        if (response.success) {
            console.log("Product added to comparison");
            console.log("Count:", response.count);
        }
    },
);
```

### 4. Remove Product

```javascript
// Remove from comparison
$.post(
    '{{ route("compare.remove") }}',
    {
        product_id: 123,
        _token: "{{ csrf_token() }}",
    },
    function (response) {
        if (response.success) {
            location.reload(); // Reload comparison page
        }
    },
);
```

### 5. Check if Product is Being Compared

```javascript
// Check if product is in comparison
$.post(
    '{{ route("compare.is.in.comparison") }}',
    {
        product_id: 123,
        _token: "{{ csrf_token() }}",
    },
    function (response) {
        if (response.isInComparison) {
            console.log("Product is being compared");
        }
    },
);
```

### 6. In a Controller or Service

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Services\Frontend\CompareService;

class ProductController extends Controller
{
    protected $compareService;

    public function __construct(CompareService $compareService)
    {
        $this->compareService = $compareService;
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        // Check if product is in comparison
        $isComparing = $this->compareService->isProductInComparison($product->id);

        // Get comparison count
        $comparisonCount = $this->compareService->getComparisonCount();

        return view('frontend.product.show', [
            'product' => $product,
            'isComparing' => $isComparing,
            'comparisonCount' => $comparisonCount
        ]);
    }
}
```

## API Responses

### Add to Comparison

**Request:**

```
POST /compare/add
product_id: 123
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Product added to comparison",
    "count": 2,
    "max": 4,
    "product": "Product Name"
}
```

**Error Response (200):**

```json
{
    "success": false,
    "message": "Maximum 4 products can be compared",
    "count": 4,
    "max": 4
}
```

### Remove from Comparison

**Request:**

```
POST /compare/remove
product_id: 123
```

**Response:**

```json
{
    "success": true,
    "message": "Product removed from comparison",
    "count": 1,
    "max": 4
}
```

### Get Comparison Count

**Request:**

```
GET /compare/count
```

**Response:**

```json
{
    "count": 2,
    "max": 4
}
```

## Features

✅ Add up to 4 products for comparison
✅ Remove individual products
✅ Clear all comparisons at once
✅ Session-based comparison (persists across page reloads)
✅ Responsive comparison table
✅ Product attribute comparison
✅ Quick add to cart from comparison page
✅ AJAX-based operations (no page reload for add/remove)
✅ Comparison count badge
✅ Toast notifications for user feedback
✅ Mobile-friendly interface

## Configuration

To change the maximum number of products that can be compared, modify the `MAX_COMPARE_ITEMS` constant in `CompareService.php`:

```php
class CompareService
{
    const MAX_COMPARE_ITEMS = 4; // Change this value
    const COMPARE_SESSION_KEY = 'product_comparison';

    // ...
}
```

## Styling

The comparison feature uses standard CSS that can be customized. The main styles are defined in:

- `resources/views/frontend/compare/index.blade.php` - Main comparison page styles
- `resources/views/frontend/compare/compare-button.blade.php` - Button styles
- `resources/views/frontend/compare/badge.blade.php` - Badge styles

Customize colors, spacing, and responsive behavior by modifying the CSS in these files.

## Performance Considerations

- Comparisons are stored in sessions (server-side)
- Uses Laravel caching for product data
- No database writes for comparison operations
- Optimized for up to 4 products
- AJAX requests prevent full page reloads

## Browser Compatibility

- Works with all modern browsers
- Requires JavaScript enabled
- Requires jQuery (already included in the project)
- Session cookies required

## Future Enhancements

Possible improvements:

1. User account comparison history
2. PDF export of comparison
3. Email comparison results
4. Comparison sharing via URL
5. Filter comparison attributes
6. Save comparison as favorites
