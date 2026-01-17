<?php

namespace App\Services\Admin;

use App\Models\Admin\Unit;

class UnitService
{
    public function createUnit(array $data): Unit
    {
        return Unit::create($data);
    }

    public function updateUnit(Unit $unit, array $data): bool
    {
        return $unit->update($data);
    }

    public function deleteUnit(int $id): bool
    {
        $unit = Unit::findOrFail($id);
        return $unit->delete();
    }

    public function changeStatus(int $id): bool
    {
        $unit = Unit::findOrFail($id);
        $status = $unit->status == 1 ? 0 : 1;
        return $unit->update(['status' => $status]);
    }
}
