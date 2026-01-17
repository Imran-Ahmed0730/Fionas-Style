<?php

namespace App\Services\Admin;

use App\Models\Admin\Attribute;

class AttributeService
{
    public function createAttribute(array $data): Attribute
    {
        return Attribute::create($data);
    }

    public function updateAttribute(Attribute $attribute, array $data): bool
    {
        return $attribute->update($data);
    }

    public function deleteAttribute(Attribute $attribute): ?bool
    {
        return $attribute->delete();
    }

    public function changeStatus(Attribute $attribute): bool
    {
        $status = $attribute->status == 1 ? 0 : 1;
        return $attribute->update([
            'status' => $status,
        ]);
    }
}
