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
        // 1. Check for active campaign discount using relationship (eager loading optimized)
        $activeCampaignProduct = $this->campaignProducts->filter(function ($cp) {
            return $cp->campaign && $cp->campaign->is_active;
        })->first();

        if ($activeCampaignProduct) {
            if ($activeCampaignProduct->discount_type == 2) { // Percent
                $discountAmount = $this->regular_price * ($activeCampaignProduct->discount / 100);
                return max(0, $this->regular_price - $discountAmount);
            } else { // Flat
                return max(0, $this->regular_price - $activeCampaignProduct->discount);
            }
        }

        // 2. Fallback to selling_price which might have individual product discount
        return $this->selling_price;
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
