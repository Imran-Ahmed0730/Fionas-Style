<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\Color;
use App\Models\Admin\Attribute;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use App\Services\Frontend\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $data['items'] = $this->productService->getShopProducts($request);
        $data['sidebar'] = $this->productService->getShopSidebarData();
        return view('frontend.product.index', $data);
    }
    public function quickView($id)
    {
        $product = Product::with([
            'category',
            'brand',
            'gallery',
            'variants',
            'campaignProducts.campaign'
        ])->findOrFail($id);

        // Pricing Logic now handled by Accessor
        $product->calculated_final_price = $product->final_price;
        $product->discount_amount = $product->regular_price - $product->final_price;

        // Improved Colors Mapping
        if ($product->color) {
            $colorNames = json_decode($product->color);
            $product->colors = Color::whereIn('name', (array) $colorNames)->get();
        }

        // Improved Attributes Mapping
        \Illuminate\Support\Facades\Log::info('QuickView Product: ' . $product->name . ' (ID: ' . $product->id . ')');
        \Illuminate\Support\Facades\Log::info('Raw attribute_values: ' . $product->attribute_values);

        if ($product->attribute_values) {
            $attributeData = json_decode($product->attribute_values, true);
            \Illuminate\Support\Facades\Log::info('Decoded attributeData: ' . print_r($attributeData, true));

            if (is_array($attributeData)) {
                $attrIds = array_keys($attributeData);
                $attributeNames = Attribute::whereIn('id', $attrIds)->pluck('name', 'id');
                \Illuminate\Support\Facades\Log::info('Attribute Names: ' . print_r($attributeNames->toArray(), true));

                $attributes_with_values = [];
                foreach ($attributeData as $attrId => $values) {
                    $attributes_with_values[] = (object) [
                        'id' => $attrId,
                        'name' => $attributeNames[$attrId] ?? 'Attribute',
                        'values' => $values
                    ];
                }
                $product->attributes_with_values = collect($attributes_with_values);
                \Illuminate\Support\Facades\Log::info('Mapped attributes_with_values count: ' . $product->attributes_with_values->count());
            } else {
                \Illuminate\Support\Facades\Log::info('attributeData is not an array');
            }
        } else {
            \Illuminate\Support\Facades\Log::info('Product has no attribute_values');
        }

        // Total Stock Calculation
        $product->total_stock = $product->variants->sum('stock_qty') ?: $product->stock_qty;

        $view = view('frontend.product.partials.quick_view', compact('product'))->render();

        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function getVariant(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('getVariant Request:', $request->all());
        $result = $this->productService->getVariant($request);

        if (!$result['success']) {
            \Illuminate\Support\Facades\Log::warning('getVariant Failed:', $result);
            return response()->json($result, 404);
        }

        return response()->json($result);
    }

    public function show($slug)
    {
        $product = $this->productService->getProductDetails($slug);

        $data['item'] = $product;
        $data['related_products'] = $this->productService->getRelatedProducts($product);

        return view('frontend.product.details', $data);
    }
    public function search(Request $request)
    {
        $data['items'] = $this->productService->getSearchProducts($request);
        $data['sidebar'] = $this->productService->getShopSidebarData();
        return view('frontend.search.index', $data);
    }
}