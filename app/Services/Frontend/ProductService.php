<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Product;
use App\Models\Admin\Color;
use App\Models\Admin\Attribute;
use App\Models\Admin\Category;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;

class ProductService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getProducts()
    {
        return Cache::remember('products', config('cache_settings.medium'), function () {
            return Product::active()
                ->whereHas('category', function ($query) {
                    $query->active();
                })
                ->with(['category', 'campaignProducts.campaign'])
                ->latest()
                ->get();
        });
    }

    /**
     * Get Shop Products with Filter and Sort
     */
    public function getShopProducts(Request $request)
    {
        // Unique Cache Key for Filters (Short cache duration as prices/stock might change)
        $cacheKey = 'shop_products_all_active';

        // 1. Fetch ALL active products with relations first (Cached)
        $products = Cache::remember($cacheKey, config('cache_settings.short'), function () {
            return Product::active()
                ->with(['category', 'brand', 'campaignProducts.campaign'])
                ->latest()
                ->get();
        });

        // 2. Filter Collection in PHP
        $filtered = $products->filter(function ($product) use ($request) {

            // Filter by Category
            if ($request->has('category') && $request->category != null) {
                $categorySlug = $request->category;
                // Check category slug or ID
                if ($product->category->slug !== $categorySlug && $product->category->id != $categorySlug) {
                    return false;
                }
            }

            // Filter by Brand
            if ($request->has('brand') && !empty($request->brand)) {
                $brands = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
                if (!in_array($product->brand->slug, $brands) && !in_array($product->brand->id, $brands)) {
                    return false;
                }
            }

            // Filter by Color
            if ($request->has('color') && !empty($request->color)) {
                $color = $request->color;
                // Assuming color is stored as JSON string in DB, we check if the string contains the color name
                if (stripos($product->color, $color) === false) {
                    return false;
                }
            }

            // Filter by Size (Attribute)
            if ($request->has('size') && !empty($request->size)) {
                $size = $request->size;
                // Assuming attribute_values is JSON string in DB
                if (stripos($product->attribute_values, $size) === false) {
                    return false;
                }
            }

            // Filter by Price Range (USING ACCESSOR: final_price)
            if ($request->filled('min_price') && $request->filled('max_price')) {
                $min = (float) $request->min_price;
                $max = (float) $request->max_price;
                $price = (float) $product->final_price; // This calls the accessor

                if ($price < $min || $price > $max) {
                    return false;
                }
            }

            return true;
        });

        // 3. Sorting (On the filtered collection)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $filtered = $filtered->sortBy('final_price'); // Sort by accessor
                    break;
                case 'price_desc':
                    $filtered = $filtered->sortByDesc('final_price'); // Sort by accessor
                    break;
                case 'latest':
                default:
                    $filtered = $filtered->sortByDesc('created_at');
                    break;
            }
        } else {
            $filtered = $filtered->sortByDesc('created_at');
        }

        // 4. Manually Paginate
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 9;
        $currentPageItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return $paginatedItems->appends($request->all());
    }

    /**
     * Get Sidebar Data
     */
    public function getShopSidebarData()
    {
        return Cache::remember('shop_sidebar_data', config('cache_settings.long'), function () {
            return [
                'categories' => Category::active()->withCount('products')->get(),
                'brands' => Brand::active()->withCount('products')->get(),
                'colors' => Color::all(),
                'attributes' => Attribute::with('attributeValues')->get(), // Assuming AttributeValues model or relationship exists if strict, otherwise just Attributes
                // 'tags' => ... (no Tag model seen yet)
            ];
        });
    }

    /**
     * Get Featured Products
     */
    public function getFeaturedProducts()
    {
        return Cache::remember('featured_products', config('cache_settings.medium'), function () {
            return Product::active()
                ->where('is_featured', 1)
                ->where('stock_qty', '>', 0)
                ->whereHas('category', function ($query) {
                    $query->active();
                })
                ->with(['category', 'campaignProducts.campaign'])
                ->latest()
                ->get();
        });
    }

    /**
     * Get Today's Deal Products
     */
    public function getTodaysDealProducts()
    {
        return Cache::remember('todays_deal_products', config('cache_settings.medium'), function () {
            return Product::active()
                ->where('include_to_todays_deal', 1)
                ->where('stock_qty', '>', 0)
                ->whereHas('category', function ($query) {
                    $query->active();
                })
                ->with(['category', 'campaignProducts.campaign'])
                ->latest()
                ->limit(10)
                ->get();
        });
    }

    /**
     * Get Latest Products
     */
    public function getLatestProducts()
    {
        return Cache::remember('latest_products', config('cache_settings.short'), function () {
            return Product::active()
                ->where('stock_qty', '>', 0)
                ->whereHas('category', function ($query) {
                    $query->active();
                })
                ->with(['category', 'campaignProducts.campaign'])
                ->latest()
                ->limit(20)
                ->get();
        });
    }
    /**
     * Get Variant by Specs
     */
    public function getVariant($request)
    {
        $productId = $request->product_id;
        $product = Cache::remember('product_variants_' . $productId, config('cache_settings.medium'), function () use ($productId) {
            return Product::with(['variants', 'campaignProducts.campaign'])->findOrFail($productId);
        });

        $colorName = $request->color_name;
        $attributes = (array) $request->input('specs', []);

        \Illuminate\Support\Facades\Log::info('Variant Search Input:', [
            'product_id' => $request->product_id,
            'color_name' => $colorName,
            'attributes' => $attributes
        ]);

        // Build the expected variant name parts
        $expectedParts = [];

        // Add color if present
        if ($colorName && !empty(trim($colorName))) {
            $expectedParts[] = trim($colorName);
        }

        // Add attributes in order
        if (!empty($attributes)) {
            foreach ($attributes as $attrValue) {
                if (!empty(trim($attrValue))) {
                    $expectedParts[] = trim($attrValue);
                }
            }
        }

        // Find the matching variant (REGEX WORD BOUNDARY MATCHING)
        $variant = $product->variants->filter(function ($v) use ($expectedParts) {
            $variantName = $v->name ?? '';

            // Check if ALL expected parts exist as WHOLE WORDS in the variant name
            // matches: "Red-L", "Red L", "L Red"
            // non-matches: "Red-XL" (when looking for L), "Green-L" (when looking for Red)

            foreach ($expectedParts as $part) {
                // Escape part for regex safety
                $safePart = preg_quote($part, '/');

                // Check for whole word match (\b) case-insensitive (i)
                if (!preg_match("/\b{$safePart}\b/i", $variantName)) {
                    return false;
                }
            }

            return true;
        })->first();

        if (!$variant) {
            \Illuminate\Support\Facades\Log::warning('Variant Not Found:', [
                'expected_parts' => $expectedParts,
                'available_variants' => $product->variants->pluck('name')
            ]);

            return [
                'success' => false,
                'debug' => [
                    'available_variants' => $product->variants->pluck('name')
                ]
            ];
        }

        // Use Accessors for Price Calculation
        $final_price = $variant->final_price;
        $regular_price = $variant->regular_price;

        return [
            'success' => true,
            'variant' => [
                'id' => $variant->id,
                'name' => $variant->name,
                'attr_name' => $variant->attr_name,
                'price' => number_format($final_price, 2, '.', ''),
                'regular_price' => number_format($regular_price, 2, '.', ''),
                'stock' => $variant->stock_qty ?? 0,
                'image' => $variant->image ? asset($variant->image) : asset($product->thumbnail)
            ]
        ];
    }

    public function getRelatedProducts(Product $product)
    {
        return Cache::remember('related_products_' . $product->id, config('cache_settings.short'), function () use ($product) {
            return Product::active()
                ->where('id', '!=', $product->id)
                ->whereHas('category', function ($query) use ($product) {
                    $query->active()->where('id', $product->category_id);
                })
                ->with(['campaignProducts.campaign'])
                ->latest()
                ->get();
        });
    }

    /**
     * Get Product Details by Slug
     */
    public function getProductDetails($slug)
    {
        return Cache::remember('product_details_' . $slug, config('cache_settings.medium'), function () use ($slug) {
            $product = Product::active()->with([
                'category',
                'brand',
                'gallery',
                'variants',
                'campaignProducts.campaign'
            ])->where('slug', $slug)->firstOrFail();

            // Pricing Logic now handled by Accessor
            $product->calculated_final_price = $product->final_price;
            $product->discount_amount = $product->regular_price - $product->final_price;

            // Improved Colors Mapping
            $product->colors = collect();
            if ($product->color) {
                $colorNames = json_decode($product->color, true);
                if (is_array($colorNames)) {
                    $product->colors = Color::whereIn('name', $colorNames)->get();
                }
            }

            // Improved Attributes Mapping
            $product->attributes_with_values = collect();
            if ($product->attribute_values) {
                $attributeData = json_decode($product->attribute_values, true);
                if (is_array($attributeData)) {
                    $attrIds = array_keys($attributeData);
                    $attributeNames = Attribute::whereIn('id', $attrIds)->pluck('name', 'id');

                    $attributes_with_values = [];
                    foreach ($attributeData as $attrId => $values) {
                        $attributes_with_values[] = (object) [
                            'id' => $attrId,
                            'name' => $attributeNames[$attrId] ?? 'Attribute',
                            'values' => $values
                        ];
                    }
                    $product->attributes_with_values = collect($attributes_with_values);
                }
            }

            // Total Stock Calculation
            $product->total_stock = $product->variants->sum('stock_qty') ?: $product->stock_qty;

            return $product;
        });
    }

    public function getSearchProducts(Request $request)
    {
        $search = $request->search;
        $type = $request->type ?? 'product'; // product, category, brand, tag

        $category = $request->category;

        // Use a cache key that includes the search term, type, and category
        $cacheKey = 'search_products_' . md5($search . $type . $category);

        // Fetch products based on type and search term
        $products = Cache::remember($cacheKey, config('cache_settings.short'), function () use ($search, $type, $category) {
            $query = Product::active()
                ->with(['category', 'brand', 'campaignProducts.campaign']);

            if (!empty($search)) {
                if ($type == 'brand') {
                    $query->whereHas('brand', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                } elseif ($type == 'category') {
                    $query->whereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                } elseif ($type == 'tag') {
                    $query->where('tags', 'LIKE', "%{$search}%");
                } else { // Default to product name search
                    $query->where('name', 'LIKE', "%{$search}%");
                }
            }

            if (!empty($category)) {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category)->orWhere('id', $category);
                });
            }

            return $query->latest()->get();
        });

        // 2. Filter Collection in PHP (for advanced filters like price range)
        $filtered = $products->filter(function ($product) use ($request) {

            // Filter by category slug if provided in sidebar
            if ($request->filled('category')) {
                if ($product->category->slug !== $request->category && $product->category->id != $request->category) {
                    return false;
                }
            }

            // Filter by Brand
            if ($request->has('brand') && !empty($request->brand)) {
                $brands = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
                if (!in_array($product->brand->slug, $brands) && !in_array($product->brand->id, $brands)) {
                    return false;
                }
            }

            // Filter by Price Range
            if ($request->filled('min_price') && $request->filled('max_price')) {
                $min = (float) $request->min_price;
                $max = (float) $request->max_price;
                $price = (float) $product->final_price;

                if ($price < $min || $price > $max) {
                    return false;
                }
            }

            return true;
        });

        // 3. Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $filtered = $filtered->sortBy('final_price');
                    break;
                case 'price_desc':
                    $filtered = $filtered->sortByDesc('final_price');
                    break;
                case 'latest':
                default:
                    $filtered = $filtered->sortByDesc('created_at');
                    break;
            }
        } else {
            $filtered = $filtered->sortByDesc('created_at');
        }

        // 4. Manual Pagination
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 12;
        $currentPageItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return $paginatedItems->appends($request->all());
    }
}
