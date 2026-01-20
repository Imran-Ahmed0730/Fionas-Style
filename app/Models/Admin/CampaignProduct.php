<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class CampaignProduct extends Model
{
    protected $guarded = ['id'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
