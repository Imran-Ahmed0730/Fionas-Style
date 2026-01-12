<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Color;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ColorController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Color Add', only: ['create']),
            new Middleware('permission:Color View', only: ['index']),
            new Middleware('permission:Color Update', only: ['edit']),
            new Middleware('permission:Color Delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Color::latest()->get();
        return view('backend.color.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.color.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required',
            'color_code' => 'required',
        ]);

        $color = Color::create($request->all());

        return redirect()->route('admin.color.index')->with('success', 'Color has been added successfully.');
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
        $data['item'] = Color::findOrFail($id);
        return view('backend.color.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required',
            'color_code' => 'required',
        ]);
        $color = Color::findOrFail($request->id);

        $color->update($request->all());

        return redirect()->route('admin.color.index')->with('success', 'Color has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $color = Color::findOrFail($request->id);
        $color->delete();
        return redirect()->route('admin.color.index')->with('success', 'Color has been deleted successfully.');
    }
}
