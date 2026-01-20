<?php

namespace App\Services\Admin;

use App\Models\Admin\Campaign;
use App\Models\Admin\CampaignProduct;
use Illuminate\Support\Str;

class CampaignService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(array $data): Campaign
    {
        // Handle thumbnail upload
        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = saveImagePath($data['thumbnail'], 'campaign');
        }

        // Generate slug from title
        $data['slug'] = Str::slug($data['title']);

        // Extract product data
        $products = $data['product_id'] ?? [];
        $discounts = $data['discount'] ?? [];
        $discountTypes = $data['discount_type'] ?? [];
        $finalPrices = $data['final_price'] ?? [];

        // Remove product data from campaign data
        unset($data['product_id'], $data['discount'], $data['discount_type'], $data['final_price']);

        // Create campaign
        $campaign = Campaign::create($data);

        // Create campaign products
        foreach ($products as $key => $productId) {
            CampaignProduct::create([
                'campaign_id' => $campaign->id,
                'product_id' => $productId,
                'discount' => $discounts[$key] ?? 0,
                'discount_type' => $discountTypes[$key] ?? 1,
                'final_price' => $finalPrices[$key] ?? 0,
            ]);
        }

        return $campaign;
    }

    public function update(Campaign $campaign, array $data): bool
    {
        // Handle thumbnail upload
        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = saveImagePath($data['thumbnail'], $campaign->thumbnail ?? null, 'campaign');
        }

        // Generate slug from title if title changed
        if (isset($data['title']) && $data['title'] !== $campaign->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Extract product data
        $products = $data['product_id'] ?? [];
        $discounts = $data['discount'] ?? [];
        $discountTypes = $data['discount_type'] ?? [];
        $finalPrices = $data['final_price'] ?? [];

        // Remove product data from campaign data
        unset($data['product_id'], $data['discount'], $data['discount_type'], $data['final_price']);

        // Update campaign
        $campaign->update($data);

        // Delete existing campaign products
        CampaignProduct::where('campaign_id', $campaign->id)->delete();

        // Create new campaign products
        foreach ($products as $key => $productId) {
            CampaignProduct::create([
                'campaign_id' => $campaign->id,
                'product_id' => $productId,
                'discount' => $discounts[$key] ?? 0,
                'discount_type' => $discountTypes[$key] ?? 1,
                'final_price' => $finalPrices[$key] ?? 0,
            ]);
        }

        return true;
    }

    public function destroy(Campaign $campaign): bool
    {
        // Delete thumbnail if exists
        if ($campaign->thumbnail && file_exists($campaign->thumbnail)) {
            unlink($campaign->thumbnail);
        }

        // Delete campaign products
        CampaignProduct::where('campaign_id', $campaign->id)->delete();

        return $campaign->delete();
    }

    public function changeStatus(Campaign $campaign): bool
    {
        return $campaign->update([
            'status' => !$campaign->status
        ]);
    }
}

