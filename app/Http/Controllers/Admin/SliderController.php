<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Slider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Admin\SliderRequest;
use App\Services\SliderService;

class SliderController extends Controller implements HasMiddleware
{
    protected $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }
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
    public function store(SliderRequest $request)
    {
        $this->sliderService->createSlider($request->validated(), $request->file('image'));
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
    public function update(SliderRequest $request)
    {
        $slider = Slider::findOrFail($request->id);
        $this->sliderService->updateSlider($slider, $request->validated(), $request->file('image'));
        return redirect()->route('admin.slider.index')->with('success', 'Slider has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->sliderService->deleteSlider($request->id);
        return redirect()->route('admin.slider.index')->with('success', 'Slider has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $this->sliderService->changeStatus($id);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
