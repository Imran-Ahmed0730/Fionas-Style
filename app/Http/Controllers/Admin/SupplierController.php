<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Admin\SupplierRequest;
use App\Services\SupplierService;

class SupplierController extends Controller implements HasMiddleware
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Supplier Add', only: ['create']),
            new Middleware('permission:Supplier View', only: ['index']),
            new Middleware('permission:Supplier Update', only: ['edit']),
            new Middleware('permission:Supplier Delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Supplier::latest()->get();
        return view('backend.supplier.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.supplier.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $this->supplierService->createSupplier($request->validated());
        return redirect()->route('admin.supplier.index')->with('message', 'Supplier added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['item'] = Supplier::findOrFail($id);
        return view('backend.supplier.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, string $id)
    {
        $supplier = Supplier::findOrFail($request->id);
        $this->supplierService->updateSupplier($supplier, $request->validated());
        return redirect()->route('admin.supplier.index')->with('message', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->supplierService->deleteSupplier($request->id);
        return back()->with('message', 'Supplier deleted successfully.');
    }

    public function changeStatus($id)
    {
        $this->supplierService->changeStatus($id);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
