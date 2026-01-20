<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = ["id"];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'applicable_for');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'applicable_products');
    }
}
