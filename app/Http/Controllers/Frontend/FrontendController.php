<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Frontend\FrontendService;

class FrontendController extends Controller
{
    protected $frontendService;

    public function __construct(FrontendService $frontendService)
    {
        $this->frontendService = $frontendService;
    }

    public function index()
    {
        $data = [
            'sliders' => $this->frontendService->getHeroSliders(),
            'featuredCategories' => $this->frontendService->getFeaturedCategories(),
            'featuredProducts' => $this->frontendService->getFeaturedProducts(),
            'todaysDealProducts' => $this->frontendService->getTodaysDealProducts(),
            'latestProducts' => $this->frontendService->getLatestProducts(),
            'categoryProducts' => $this->frontendService->getCategoryWiseProducts(),
            'blogs' => $this->frontendService->getLatestBlogs(),
        ];

        return view('frontend.home.index', $data);
    }

    public function shop()
    {
        return view('frontend.home.index'); // Placeholder
    }

    public function category($slug)
    {
        return view('frontend.home.index'); // Placeholder
    }

    public function allCategories()
    {
        return view('frontend.home.index'); // Placeholder
    }

    public function lifestyle()
    {
        return view('frontend.home.index'); // Placeholder
    }
}
