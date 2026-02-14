<?php

namespace App\Models\Admin;

use App\Traits\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = ['id'];

    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class, 'payment_method_id');
    }
}
