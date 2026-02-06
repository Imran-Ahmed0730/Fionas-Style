<?php

namespace App\Models\Admin;

use App\Traits\HasActiveScope;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
}
