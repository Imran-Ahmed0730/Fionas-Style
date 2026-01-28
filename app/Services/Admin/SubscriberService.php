<?php

namespace App\Services\Admin;

use App\Models\Admin\Subscriber;

class SubscriberService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(array $data){
        return Subscriber::create($data);
    }
}
