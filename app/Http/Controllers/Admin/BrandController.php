<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BrandRequest;
use App\Services\BrandService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class BrandController extends Controller implements HasMiddleware
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Brand Add', only: ['create']),
            new Middleware('permission:Brand View', only: ['index']),
            new Middleware('permission:Brand Update', only: ['edit']),
            new Middleware('permission:Brand Delete', only: ['destroy']),
            new Middleware('permission:Brand Status Change', only: ['changeStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Brand::latest()->get();
        return view('backend.brand.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.brand.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        $this->brandService->createBrand($request->validated(), $request->file('image'), $request->file('meta_image'));
        return redirect()->route('admin.brand.index')->with('success', 'Brand has been added successfully.');
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
        $data['item'] = Brand::findOrFail($id);
        return view('backend.brand.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request)
    {
        $brand = Brand::findOrFail($request->id);
        $this->brandService->updateBrand($brand, $request->validated(), $request->file('image'), $request->file('meta_image'));
        return redirect()->route('admin.brand.index')->with('success', 'Brand has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $brand = Brand::findOrFail($request->id);
        $this->brandService->deleteBrand($brand);
        return redirect()->route('admin.brand.index')->with('success', 'Brand has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $brand = Brand::findOrFail($id);
        $this->brandService->changeStatus($brand);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
