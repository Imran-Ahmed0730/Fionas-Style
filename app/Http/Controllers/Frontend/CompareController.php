<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CompareService;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    protected $compareService;

    public function __construct(CompareService $compareService)
    {
        $this->compareService = $compareService;
    }

    /**
     * Display comparison page
     */
    public function index()
    {
        $data = $this->compareService->getComparisonData();
        return view('frontend.compare.index', $data);
    }

    /**
     * Add product to comparison
     */
    public function add(Request $request)
    {
        $productId = $request->get('product_id');

        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        $result = $this->compareService->addToComparison($productId);

        return response()->json($result);
    }

    /**
     * Remove product from comparison
     */
    public function remove(Request $request)
    {
        $productId = $request->get('product_id');

        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        $result = $this->compareService->removeFromComparison($productId);

        return response()->json($result);
    }

    /**
     * Clear all comparisons
     */
    public function clear(Request $request)
    {
        $result = $this->compareService->clearComparison();

        return response()->json($result);
    }

    /**
     * Get comparison count (for AJAX)
     */
    public function getCount()
    {
        return response()->json([
            'count' => $this->compareService->getComparisonCount(),
            'max' => 4
        ]);
    }

    /**
     * Check if product is in comparison
     */
    public function isInComparison(Request $request)
    {
        $productId = $request->get('product_id');

        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        $isInComparison = $this->compareService->isProductInComparison($productId);

        return response()->json([
            'success' => true,
            'isInComparison' => $isInComparison,
            'productId' => $productId
        ]);
    }
}
