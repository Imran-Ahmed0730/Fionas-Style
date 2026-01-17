<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;
use App\Models\Admin\Color;
use App\Services\Admin\ColorService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }
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
    public function store(ColorRequest $request)
    {
        $this->colorService->createColor($request->validated());
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
    public function update(ColorRequest $request)
    {
        $color = Color::findOrFail($request->id);
        $this->colorService->updateColor($color, $request->validated());
        return redirect()->route('admin.color.index')->with('success', 'Color has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $color = Color::findOrFail($request->id);
        $this->colorService->deleteColor($color);
        return redirect()->route('admin.color.index')->with('success', 'Color has been deleted successfully.');
    }
}
