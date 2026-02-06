<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Campaign;
use App\Services\Frontend\ProductService;

class CampaignController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $campaigns = Campaign::where('status', 1)
            ->with(['campaignProducts.product.category'])
            ->get()
            ->filter->isActive
            ->values();

        return view('frontend.campaign.index', compact('campaigns'));
    }

    public function show($slug, Request $request)
    {
        $campaign = Campaign::where('slug', $slug)->with(['campaignProducts.product.category'])->firstOrFail();

        // collect active products for this campaign
        $products = $campaign->campaignProducts->map(function ($cp) {
            return $cp->product;
        })->filter()->values();

        // Manual pagination like ProductService
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 12;
        $currentPageItems = $products->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $sidebar = $this->productService->getShopSidebarData();

        return view('frontend.campaign.show', ['campaign' => $campaign, 'items' => $paginatedItems, 'sidebar' => $sidebar]);
    }
}
