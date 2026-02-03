<?php

namespace App\Services\Admin;

use App\Models\Admin\AccountHead;

class AccountHeadService
{
    public function getAll()
    {
        return AccountHead::latest()->get();
    }

    public function store(array $data)
    {
        return AccountHead::create($data);
    }

    public function update(AccountHead $accountHead, array $data)
    {
        return $accountHead->update($data);
    }

    public function delete(AccountHead $accountHead)
    {
        if ($accountHead->editable == 0) {
            return false;
        }
        return $accountHead->delete();
    }

    public function changeStatus(AccountHead $accountHead)
    {
        $accountHead->status = $accountHead->status == 1 ? 0 : 1;
        return $accountHead->save();
    }
}
