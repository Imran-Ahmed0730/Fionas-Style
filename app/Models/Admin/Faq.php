<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
}
