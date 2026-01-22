<?php

namespace App\Services\Admin;

use App\Models\Admin\Product;
use App\Models\Admin\Coupon;
use Illuminate\Support\Str;

class CouponService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getProducts(): Product
    {
        return Product::active()->orderBy('name', 'asc')->get();
    }

    public function generateCode(): string
    {
        $code_syntax = getSetting('coupon_code_syntax');
        do {
            $code = Str::replace('[[random_number]]', '', $code_syntax) . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $isUnique = !Coupon::where('code', $code)->exists();
        } while (!$isUnique);

        return $code;
    }

    public function store(array $data): Coupon
    {
        if (isset($data['applicable_products'])) {
            $data['applicable_products'] = json_encode($data['applicable_products']);
        }
        else{
            $data['applicable_products'] = json_encode([]);
        }
        return Coupon::create($data);
    }

    public function update(Coupon $coupon, array $data): bool
    {
        if (isset($data['applicable_products'])) {
            $data['applicable_products'] = json_encode($data['applicable_products']);
        }
        else{
            $data['applicable_products'] = json_encode([]);
        }
        return $coupon->update($data);
    }

    public function destroy(Coupon $coupon): bool
    {
        return $coupon->delete();
    }

    public function changeStatus(Coupon $coupon): bool
    {
        return $coupon->update([
            'status' => !$coupon->status
        ]);
    }
}
