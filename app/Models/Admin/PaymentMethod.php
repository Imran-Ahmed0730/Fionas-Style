<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class, 'payment_method_id');
    }
}
