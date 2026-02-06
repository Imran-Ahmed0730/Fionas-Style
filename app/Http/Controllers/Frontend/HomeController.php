<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UserMessageRequest;
use Illuminate\Http\Request;

use App\Services\Frontend\HomeService;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;
use App\Services\Frontend\PageService;
use App\Services\Frontend\UserMessageService;
use App\Services\Frontend\FaqService;

class HomeController extends Controller
{
    protected $homeService;
    protected $categoryService;
    protected $productService;
    protected $pageService;
    protected $userMessageService;
    protected $faqService;

    public function __construct(
        HomeService $homeService,
        CategoryService $categoryService,
        ProductService $productService,
        PageService $pageService,
        UserMessageService $userMessageService,
        FaqService $faqService
    ) {
        $this->homeService = $homeService;
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->pageService = $pageService;
        $this->userMessageService = $userMessageService;
        $this->faqService = $faqService;
    }

    public function index()
    {
        $data = [
            'sliders' => $this->homeService->getHeroSliders(),
            'featuredCategories' => $this->categoryService->getFeaturedCategories(),
            'featuredProducts' => $this->productService->getFeaturedProducts(),
            'todaysDealProducts' => $this->productService->getTodaysDealProducts(),
            'campaigns' => $this->homeService->getActiveCampaigns(),
            'latestProducts' => $this->productService->getLatestProducts(),
            'categoryProducts' => $this->categoryService->getCategoryWiseProducts(),
            'blogs' => $this->homeService->getLatestBlogs(),
            'brands' => $this->homeService->getBrands(),
        ];

        return view('frontend.home.index', $data);
    }

    public function shop()
    {
        return redirect()->route('shop');
    }

    public function category($slug)
    {
        return redirect()->route('category', $slug);
    }

    public function allCategories()
    {
        return redirect()->route('categories');
    }

    public function lifestyle()
    {
        return view('frontend.lifestyle');
    }

    public function contact()
    {
        return view('frontend.contact.index');
    }

    public function contactSubmit(UserMessageRequest $request)
    {
        try {
            $this->userMessageService->storeMessage($request->validated());
            return redirect()->back()->with('success', 'Message sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send message. Please try again.');
        }
    }

    public function aboutUs()
    {
        $data['item'] = $this->pageService->getPageBySlug('about-us');
        return view('frontend.page.index', $data);
    }

    public function faq()
    {
        $data['items'] = $this->faqService->getFaqs();
        return view('frontend.faq.index', $data);
    }

    public function orderTrackForm()
    {
        return view('frontend.order.track');
    }

    public function orderTrackSubmit(Request $request)
    {
        $request->validate([
            'invoice' => 'required|string',
        ]);

        $invoice = $request->input('invoice');
        $order = \App\Models\Admin\Order::where('invoice_no', $invoice)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found with provided invoice number');
        }

        return redirect()->route('checkout.summary', $invoice);
    }

    public function privacyPolicy()
    {
        $data['item'] = $this->pageService->getPageBySlug('privacy-policy');
        return view('frontend.page.index', $data);
    }

    public function termsConditions()
    {
        $data['item'] = $this->pageService->getPageBySlug('terms-conditions');
        return view('frontend.page.index', $data);
    }
}
