<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasActiveScope;

class Brand extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
