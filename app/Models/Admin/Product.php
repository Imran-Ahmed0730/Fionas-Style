<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasActiveScope;

class Product extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function gallery()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_name', 'name');
    }
}
