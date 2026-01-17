<?php

namespace App\Services\Admin;

use App\Models\Admin\AttributeValue;

class AttributeValueService
{
    public function createAttributeValue(array $data): AttributeValue
    {
        return AttributeValue::create($data);
    }

    public function updateAttributeValue(AttributeValue $attributeValue, array $data): bool
    {
        return $attributeValue->update($data);
    }

    public function deleteAttributeValue(AttributeValue $attributeValue): ?bool
    {
        return $attributeValue->delete();
    }
}
