<?php

namespace App\Models\Admin;

use App\Traits\HasActiveScope;
use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    use HasActiveScope;
    protected $guarded = ['id'];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id');
    }
}
