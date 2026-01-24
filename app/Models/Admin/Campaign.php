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

    public function getIsActiveAttribute()
    {
        if ($this->status != 1) {
            return false;
        }

        if (empty($this->duration)) {
            return false;
        }

        $dates = explode(' to ', $this->duration);
        if (count($dates) != 2) {
            return false;
        }

        $start = \Carbon\Carbon::parse($dates[0]);
        $end = \Carbon\Carbon::parse($dates[1]);
        $now = now();

        return $now->betweenIncluded($start, $end);
    }
}
