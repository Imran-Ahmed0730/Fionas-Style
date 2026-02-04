<?php

namespace App\Services\Frontend;

use App\Models\Admin\UserMessage;

class UserMessageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeMessage(array $data)
    {
        return UserMessage::create($data);
    }
}
