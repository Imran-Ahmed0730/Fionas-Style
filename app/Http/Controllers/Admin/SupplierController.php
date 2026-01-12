<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SupplierController extends Controller implements HasMiddleware
{
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
            'address' => 'required',
        ]);

        Supplier::create($request->all());
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
            'address' => 'required',
        ]);

        $supplier = Supplier::findOrFail($request->id);

        $supplier->update($request->all());
        return redirect()->route('admin.supplier.index')->with('message', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Supplier::destroy($request->id);
        return back()->with('message', 'Supplier deleted successfully.');
    }

    public function changeStatus($id)
    {
        $supplier = Supplier::findOrFail($id);
        if ($supplier->status == 1) {
            $supplier->status = 0;
        }
        else {
            $supplier->status = 1;
        }
        $supplier->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
