<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function campaignProducts()
    {
        return $this->hasMany(CampaignProduct::class, 'campaign_id');
    }
}
