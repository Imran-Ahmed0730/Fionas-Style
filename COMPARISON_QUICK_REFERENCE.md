# Product Comparison - Quick Reference Guide

## 🎯 Feature Overview

The Product Comparison feature allows customers to:

- Compare up to 4 products side-by-side
- View product attributes in a comparison table
- Add/remove products from comparison
- Persist comparison across page navigation
- Quick add to cart from comparison page

---

## 📍 User Interface Locations

### 1. **Header Navigation**

- **Location:** Top-right navigation bar
- **Icon:** Exchange icon (⇄)
- **Shows:** Comparison product count
- **Action:** Click to go to comparison page

### 2. **Product Cards**

- **Location:** Bottom of product image area
- **Icon:** Loop icon (⟳)
- **Pages:** Appears on all product listings
- **Action:** Click to add/remove from comparison

### 3. **Comparison Page**

- **URL:** `/compare`
- **Features:**
    - Side-by-side product comparison table
    - Product images, names, prices
    - Attribute breakdown
    - Remove/Add to cart buttons

---

## 🔧 Developer Integration

### Include Comparison Features

#### Option 1: Automatic (Already Done)

All pages using the master template automatically have:

- Comparison badge in header
- Compare buttons on product cards
- Comparison functionality

#### Option 2: Manual in Custom Views

```blade
<!-- Add to header for comparison badge -->
<?php $compareService = app(\App\Services\Frontend\CompareService::class); ?>
<span id="comparisonCount">{{ $compareService->getComparisonCount() }}</span>

<!-- Add to product cards for compare button -->
<a onclick="ProductComparison.toggleProduct({{ $product->id }}, handleComparisonResponse)">
    <i class="ti-loop"></i> Compare
</a>
```

---

## 💻 JavaScript API

### Core Methods

#### 1. Toggle Product (Add/Remove)

```javascript
ProductComparison.toggleProduct(productId, callbackFunction);

// Example
ProductComparison.toggleProduct(123, function (response) {
    if (response.success) {
        console.log("Success:", response.message);
        console.log("Count:", response.count, "/", response.max);
    }
});
```

#### 2. Add Product

```javascript
ProductComparison.addProduct(productId, callback);
```

#### 3. Remove Product

```javascript
ProductComparison.removeProduct(productId, callback);
```

#### 4. Get Comparison Count

```javascript
ProductComparison.getCount(function (response) {
    console.log("Compared:", response.count + "/" + response.max);
});
```

#### 5. Clear All Comparisons

```javascript
ProductComparison.clearAll(callback);
```

#### 6. Check if Product is Compared

```javascript
ProductComparison.isInComparison(productId, function (response) {
    if (response.isInComparison) {
        console.log("Product is being compared");
    }
});
```

#### 7. Show Notification

```javascript
ProductComparison.notify("Message here", "success");
// Types: success, error, info, warning
// Duration: default 3000ms
ProductComparison.notify("Warning message", "warning", 5000);
```

---

## 📊 Response Format

### Success Response

```json
{
    "success": true,
    "message": "Product added to comparison",
    "count": 2,
    "max": 4,
    "product": "Product Name"
}
```

### Error Response

```json
{
    "success": false,
    "message": "Maximum 4 products can be compared",
    "count": 4,
    "max": 4
}
```

---

## 🔌 Backend Integration

### Service Methods

#### CompareService

```php
$compareService = app(\App\Services\Frontend\CompareService::class);

// Get all compared products
$products = $compareService->getComparedProducts();

// Get compared product IDs
$ids = $compareService->getComparedProductIds();

// Add to comparison
$result = $compareService->addToComparison($productId);

// Remove from comparison
$result = $compareService->removeFromComparison($productId);

// Get count
$count = $compareService->getComparisonCount();

// Check if product is compared
$isCompared = $compareService->isProductInComparison($productId);

// Clear all
$compareService->clearComparison();

// Get full comparison data
$data = $compareService->getComparisonData();
```

### In Controller

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Services\Frontend\CompareService;

class SomeController extends Controller
{
    protected $compareService;

    public function __construct(CompareService $compareService)
    {
        $this->compareService = $compareService;
    }

    public function someMethod()
    {
        $comparisonCount = $this->compareService->getComparisonCount();
        $isCompared = $this->compareService->isProductInComparison($productId);

        return view('some.view', [
            'comparisonCount' => $comparisonCount,
            'isCompared' => $isCompared
        ]);
    }
}
```

---

## 🎨 Customization

### Styling

#### Button Styling

```css
.btn-compare-product-item {
    /* Your custom styles */
    cursor: pointer;
    color: #333;
    transition: all 0.3s;
}

.btn-compare-product-item:hover {
    color: #e7ab3c;
}
```

#### Badge Styling

```css
#comparisonCount {
    background: #e7ab3c;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
```

### Change Max Products

File: `app/Services/Frontend/CompareService.php`

```php
const MAX_COMPARE_ITEMS = 4; // Change this value
```

### Customize Attributes

File: `app/Services/Frontend/CompareService.php` → `getComparisonData()` method

```php
$attributes = [
    'brand' => ['label' => 'Brand', 'key' => 'brand'],
    'category' => ['label' => 'Category', 'key' => 'category'],
    // Add more attributes here
];
```

---

## 🔐 Security

✅ **CSRF Protected:** All AJAX requests include CSRF token
✅ **Data Validation:** Product IDs validated before processing
✅ **Session-Based:** No sensitive data exposed in URLs
✅ **Error Handling:** Graceful error messages

---

## ⚡ Performance

- **No Database Writes:** Uses Laravel Sessions
- **Server-Side Storage:** Secure and persistent
- **AJAX Only:** No full page reloads
- **Cached Queries:** Product data uses existing caches
- **Light-Weight JS:** ~10KB uncompressed

---

## 🐛 Debugging

### Check Browser Console

```javascript
// Test API
ProductComparison.toggleProduct(1, function (r) {
    console.log(r);
});

// Get count
ProductComparison.getCount(console.log);

// Check CSRF token
console.log(ProductComparison.getCsrfToken());
```

### Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

### Verify Data

```php
// In Tinker or controller
$compareService = app(\App\Services\Frontend\CompareService::class);
dd($compareService->getComparisonData());
```

---

## 📝 Configuration

### Session Configuration

File: `.env`

```
SESSION_DRIVER=file  # or cookie, database, etc.
SESSION_LIFETIME=120
```

### Session Key

File: `app/Services/Frontend/CompareService.php`

```php
const COMPARE_SESSION_KEY = 'product_comparison';
```

---

## 🚀 Implementation Checklist

- [x] CompareService created
- [x] CompareController implemented
- [x] Routes registered
- [x] Comparison views created
- [x] Product item template updated
- [x] Header navigation updated
- [x] JavaScript module created
- [x] CSS styling added
- [x] CSRF token added to master template
- [x] Global response handler added
- [x] Documentation created

---

## 📚 File Locations

| Component    | File                                                               |
| ------------ | ------------------------------------------------------------------ |
| Service      | `app/Services/Frontend/CompareService.php`                         |
| Controller   | `app/Http/Controllers/Frontend/CompareController.php`              |
| Routes       | `routes/web.php`                                                   |
| Main View    | `resources/views/frontend/compare/index.blade.php`                 |
| Button       | `resources/views/frontend/compare/compare-button.blade.php`        |
| Badge        | `resources/views/frontend/compare/badge.blade.php`                 |
| Product Info | `resources/views/frontend/compare/product-info.blade.php`          |
| JavaScript   | `public/frontend/js/product-comparison.js`                         |
| Product Item | `resources/views/frontend/product/partials/product_item.blade.php` |
| Header       | `resources/views/frontend/include/header.blade.php`                |
| Master       | `resources/views/frontend/master.blade.php`                        |

---

## 🔗 Related Features

- **Shopping Cart:** `/cart` - Add products to cart
- **Wishlist:** `/customer/wishlist` - Save products for later
- **Product Details:** `/product/{slug}` - View product information
- **Search:** `/search` - Find products
- **Categories:** `/category/{slug}` - Browse by category

---

## 📞 Support & Troubleshooting

### Common Issues

**1. Compare button not working**

- Check browser console for errors
- Verify CSRF token in page source
- Ensure jQuery is loaded

**2. Count not updating**

- Verify comparisonCount element exists
- Check response handler is called
- Clear browser cache

**3. Comparison not persisting**

- Check SESSION_DRIVER in .env
- Verify session storage directory exists
- Check browser cookies are enabled

**4. Products not showing**

- Verify products are active in database
- Check session data with `dd(session()->get('product_comparison'))`
- Reload comparison page

---

## 🎓 Learning Resources

- [Laravel Sessions](https://laravel.com/docs/sessions)
- [jQuery AJAX](https://api.jquery.com/jQuery.post/)
- [Toastr Notifications](https://github.com/CodeSeven/toastr)
- [Font Awesome Icons](https://fontawesome.com/icons)

---

**Last Updated:** February 7, 2026
**Version:** 1.0
**Status:** Production Ready ✅
