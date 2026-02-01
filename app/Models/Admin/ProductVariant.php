<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = ["id"];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute()
    {
        // 1. Retrieve parent product
        $product = $this->product;

        if ($product) {
            // Check for active campaign discount on parent
            $activeCampaignProduct = $product->campaignProducts->filter(function ($cp) {
                return $cp->campaign && $cp->campaign->is_active;
            })->first();

            if ($activeCampaignProduct) {
                // Apply campaign discount to Variant's Regular Price
                if ($activeCampaignProduct->discount_type == 2) { // Percent
                    $discountAmount = $this->regular_price * ($activeCampaignProduct->discount / 100);
                    return max(0, $this->regular_price - $discountAmount);
                } else { // Flat
                    return max(0, $this->regular_price - $activeCampaignProduct->discount);
                }
            }

            // 2. Check for Product Level Discount (Applied to variant)
            if ($product->discount > 0) {
                if ($product->discount_type == 2) { // Percentage
                    $discountAmount = $this->regular_price * ($product->discount / 100);
                    return max(0, $this->regular_price - $discountAmount);
                } else { // Flat
                    // Assume flat discount applies to variant unit price as well
                    return max(0, $this->regular_price - $product->discount);
                }
            }
        }

        // 3. Fallback: Variant's explicit final/selling price, else Regular
        return $this->attributes['selling_price'] ?? $this->regular_price;
    }
}
