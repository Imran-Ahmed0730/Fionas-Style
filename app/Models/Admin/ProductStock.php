<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductStock extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected function addedOn(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? date('d M, Y h:i A', strtotime($value)) : 'N/A',
            set: fn($value) => $value ?? now(),
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_sku', 'sku');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id')->withDefault([
            'name' => 'N/A',
            'phone' => 'N/A',
        ]);
    }
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by', 'id')->withDefault([
            'name' => 'N/A',
        ]);
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withDefault([
            'name' => 'N/A',
        ]);
    }


}
