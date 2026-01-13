<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Slider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SliderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Slider Add', only: ['create']),
            new Middleware('permission:Slider View', only: ['index']),
            new Middleware('permission:Slider Update', only: ['edit']),
            new Middleware('permission:Slider Delete', only: ['destroy']),
            new Middleware('permission:Slider Status Change', only: ['changeStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Slider::latest()->get();
        return view('backend.slider.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.slider.form');
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
        $imagePath = saveImagePath($request->file('image'), null,'slider');
        $slider = Slider::create([
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

        return redirect()->route('admin.slider.index')->with('success', 'Slider has been added successfully.');
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
        $data['item'] = Slider::findOrFail($id);
        return view('backend.slider.form', $data);
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
        $slider = Slider::findOrFail($request->id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = saveImagePath($image, $slider->image, 'slider' );

        }
        else{
            $imagePath = $slider->image;
        }
        $slider->update([
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

        return redirect()->route('admin.slider.index')->with('success', 'Slider has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $slider = Slider::findOrFail($request->id);
        if ($slider->image && file_exists($slider->image)) {
            unlink($slider->image);
        }
        $slider->delete();
        return redirect()->route('admin.slider.index')->with('success', 'Slider has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $slider = Slider::findOrFail($id);
        $status = 1;
        if($slider->status == 1){
            $status = 0;
        }
        $slider->update([
            'status' => $status,
        ]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
