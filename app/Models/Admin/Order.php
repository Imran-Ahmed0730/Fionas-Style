<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'confirmed_at' => 'date',
        'shipped_at' => 'date',
        'delivered_at' => 'date',
        'cancelled_at' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function appliedCoupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

}
