<?php

namespace App\Services\Frontend;

use App\Models\Frontend\ProductReview;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\Log;

class ProductReviewService
{
    /**
     * Store a new product review
     *
     * @param array $data
     * @return ProductReview
     */
    public function store(array $data)
    {
        try {
            return ProductReview::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating product review: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateProductData(Product $product)
    {
        $product->update([
            'rating' => $product->reviews()->avg('rating'),
            'review_count' => $product->reviews()->count(),
        ]);
    }
}
