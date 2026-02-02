<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;
use App\Models\Admin\Product;
class CategoryController extends Controller
{
    private $categoryService, $productService;

    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }
    public function index()
    {
        $data['categories'] = $this->categoryService->getAllCategories();
        return view('frontend.category.index', $data);
    }
    public function category($slug, Request $request)
    {
        $data['item'] = $this->categoryService->getCategoryBySlug($slug);

        // Fetch products for this category with pagination and standard filters
        $query = Product::active()
            ->where('category_id', $data['item']->id)
            ->with(['category', 'brand', 'campaignProducts.campaign']);

        // Filter by Brand
        if ($request->has('brand') && !empty($request->brand)) {
            $brands = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $query->whereHas('brand', function ($q) use ($brands) {
                $q->whereIn('slug', $brands)->orWhereIn('id', $brands);
            });
        }

        // Filter by Price Range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('selling_price', [$request->min_price, $request->max_price]);
        }

        // Persist sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('selling_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('selling_price', 'desc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $data['products'] = $query->paginate(12)->appends(request()->query());
        $data['sidebar'] = $this->productService->getShopSidebarData();

        return view('frontend.category.details', $data);
    }
}
