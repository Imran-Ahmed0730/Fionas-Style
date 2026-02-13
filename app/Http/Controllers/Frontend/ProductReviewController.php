<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProductReviewRequest;
use App\Services\Frontend\ProductReviewService;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Product;
class ProductReviewController extends Controller
{
    protected $productReviewService;
    public function __construct(ProductReviewService $productReviewService)
    {
        $this->productReviewService = $productReviewService;
    }
    public function store(ProductReviewRequest $request, $productId)
    {
        $user = Auth::user();

        // Create the review
        $data = $request->validated();
        $data['user_id'] = $user->id;

        $this->productReviewService->store($data);

        // Update product rating and review count
        $product = Product::findOrFail($productId);
        $this->productReviewService->updateProductData($product);

        return back()->with('success', 'Thank you for your review!');
    }
}
