<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasActiveScope;

class Product extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];
    protected static function booted()
    {
        static::deleting(function ($product) {
            CampaignProduct::where('product_id', $product->id)->delete();
        });
    }

    public function getBuyingPriceAttribute()
    {
        return $this->stocks->first()->buying_price ?? 0;
    }

    public function getFinalPriceAttribute()
    {
        // 1. Check for active campaign discount
        $campaignProducts = CampaignProduct::where('product_id', $this->id)
            ->with(['campaign'])
            ->get();

        $activeCampaignProduct = null;
        foreach ($campaignProducts as $cp) {
            if ($cp->campaign && $cp->campaign->is_active) {
                $activeCampaignProduct = $cp;
                break;
            }
        }

        if ($activeCampaignProduct) {
            if ($activeCampaignProduct->discount_type == 2) { // Percent
                $discountAmount = $this->regular_price * ($activeCampaignProduct->discount / 100);
                return max(0, $this->regular_price - $discountAmount);
            } else { // Flat
                return max(0, $this->regular_price - $activeCampaignProduct->discount);
            }
        }

        // 2. Check for product specific discount (if you have fields for that in products table)
        // assuming no specific product discount logic requested other than campaign for now, 
        // or if it exists it would be similar logic here.
        // For now returning regular or buying price if no campaign.

        return $this->regular_price;
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function gallery()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_name', 'name');
    }

    public function campaignProducts()
    {
        return $this->hasMany(CampaignProduct::class);
    }
}
