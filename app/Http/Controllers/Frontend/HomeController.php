<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Frontend\HomeService;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;

class HomeController extends Controller
{
    protected $homeService;
    protected $categoryService;
    protected $productService;

    public function __construct(
        HomeService $homeService,
        CategoryService $categoryService,
        ProductService $productService,
    ) {
        $this->homeService = $homeService;
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    public function index()
    {
        $data = [
            'sliders'              => $this->homeService->getHeroSliders(),
            'featuredCategories'   => $this->categoryService->getFeaturedCategories(),
            'featuredProducts'     => $this->productService->getFeaturedProducts(),
            'todaysDealProducts'   => $this->productService->getTodaysDealProducts(),
            'latestProducts'       => $this->productService->getLatestProducts(),
            'categoryProducts'     => $this->categoryService->getCategoryWiseProducts(),
            'blogs'                => $this->homeService->getLatestBlogs(),
            'brands'               => $this->homeService->getBrands(),
        ];

        return view('frontend.home.index', $data);
    }

    public function shop()
    {
        return view('frontend.home.index');
    }

    public function category($slug)
    {
        return view('frontend.home.index');
    }

    public function allCategories()
    {
        return view('frontend.home.index');
    }

    public function lifestyle()
    {
        return view('frontend.home.index');
    }
}
