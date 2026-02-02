<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'invoice_no',
        'customer_id',
        'name',
        'email',
        'phone',
        'subtotal',
        'tax',
        'shipping_cost',
        'free_shipping',
        'discount',
        'discount_type',
        'coupon_id',
        'coupon_discount',
        'grand_total',
        'payment_status',
        'payment_method',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'note',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'status',
        'created_by',
        'updated_by',
    ];

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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
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
}
