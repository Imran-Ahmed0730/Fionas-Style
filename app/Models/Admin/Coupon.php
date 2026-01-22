<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Coupon extends Model
{
    protected $guarded = ["id"];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getDuration():Attribute
    {
        return Attribute::make(
            get: fn($value) => date("D, d M Y h:i A", strtotime($this->end_date)). " - " . date("D, d M Y h:i A", strtotime($this->start_date))
        );
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'applicable_for');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'applicable_products');
    }
}
