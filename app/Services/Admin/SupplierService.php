<?php

namespace App\Services\Admin;

use App\Models\Admin\Supplier;

class SupplierService
{
    public function createSupplier(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function updateSupplier(Supplier $supplier, array $data): bool
    {
        return $supplier->update($data);
    }

    public function deleteSupplier(int $id): bool
    {
        return Supplier::destroy($id);
    }

    public function changeStatus(int $id): bool
    {
        $supplier = Supplier::findOrFail($id);
        $status = $supplier->status == 1 ? 0 : 1;
        $supplier->status = $status;
        return $supplier->save();
    }
}
