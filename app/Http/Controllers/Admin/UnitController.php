<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Unit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class UnitController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Unit Add', only: ['create']),
            new Middleware('permission:Unit View', only: ['index']),
            new Middleware('permission:Unit Update', only: ['edit']),
            new Middleware('permission:Unit Delete', only: ['destroy']),
            new Middleware('permission:Unit Status Change', only: ['changeStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Unit::latest()->get();
        return view('backend.unit.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.unit.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required',
        ]);

        $unit = Unit::create($request->all());

        return redirect()->route('admin.unit.index')->with('success', 'Unit has been added successfully.');
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
        $data['item'] = Unit::findOrFail($id);
        return view('backend.unit.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required',
        ]);
        $unit = Unit::findOrFail($request->id);

        $unit->update($request->all());

        return redirect()->route('admin.unit.index')->with('success', 'Unit has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $unit = Unit::findOrFail($request->id);
        $unit->delete();
        return redirect()->route('admin.unit.index')->with('success', 'Unit has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $unit = Unit::findOrFail($id);
        $status = 1;
        if($unit->status == 1){
            $status = 0;
        }
        $unit->update([
            'status' => $status,
        ]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
