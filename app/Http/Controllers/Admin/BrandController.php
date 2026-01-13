<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class BrandController extends Controller implements HasMiddleware
{
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
    public function store(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required|unique:brands,name',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        $imagePath = saveImagePath($request->file('image'), null,'brand');
        $brand = Brand::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'status' => $request->status,

        ]);

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
    public function update(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required|unique:brands,name',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg',
        ]);
        $brand = Brand::findOrFail($request->id);
        if($brand->name != $request->name){
            $request->validate([
                'name' => 'unique:brands,name'
            ]);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = saveImagePath($image, $brand->image, 'brand' );

        }
        else{
            $imagePath = $brand->image;
        }
        $brand->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'status' => $request->status,

        ]);

        return redirect()->route('admin.brand.index')->with('success', 'Brand has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $brand = Brand::findOrFail($request->id);
        if ($brand->image && file_exists($brand->image)) {
            unlink($brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brand.index')->with('success', 'Brand has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $brand = Brand::findOrFail($id);
        $status = 1;
        if($brand->status == 1){
            $status = 0;
        }
        $brand->update([
            'status' => $status,
        ]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
