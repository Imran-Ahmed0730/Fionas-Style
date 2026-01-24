<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class)->withDefault([
            'name'=> 'N/A',
        ]);
    }
}
