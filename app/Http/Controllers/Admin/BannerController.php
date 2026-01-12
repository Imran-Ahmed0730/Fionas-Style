<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class BannerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Banner Add', only: ['create']),
            new Middleware('permission:Banner View', only: ['index']),
            new Middleware('permission:Banner Update', only: ['edit']),
            new Middleware('permission:Banner Delete', only: ['destroy']),
            new Middleware('permission:Banner Status Change', only: ['changeStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Banner::latest()->get();
        return view('backend.banner.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.banner.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return $request;
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        $imagePath = saveImagePath($request->file('image'), 'banner');
        $banner = Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'btn_text' => $request->btn_text,
            'link' => $request->link,
            'image' => $imagePath,
            'priority' => $request->priority,
            'position' => $request->position,
            'status' => $request->status,

        ]);

        return redirect()->route('admin.banner.index')->with('success', 'Banner has been added successfully.');
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
        $data['item'] = Banner::findOrFail($id);
        return view('backend.banner.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
//        return $request;
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg',
        ]);
        $banner = Banner::findOrFail($request->id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = updateImagePath($image, $banner->image, 'banner' );

        }
        else{
            $imagePath = $banner->image;
        }
        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'btn_text' => $request->btn_text,
            'link' => $request->link,
            'image' => $imagePath,
            'priority' => $request->priority,
            'position' => $request->position,
            'status' => $request->status,

        ]);

        return redirect()->route('admin.banner.index')->with('success', 'Banner has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        if ($banner->image && file_exists($banner->image)) {
            unlink($banner->image);
        }
        $banner->delete();
        return redirect()->route('admin.banner.index')->with('success', 'Banner has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $status = 1;
        if($banner->status == 1){
            $status = 0;
        }
        $banner->update([
            'status' => $status,
        ]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
