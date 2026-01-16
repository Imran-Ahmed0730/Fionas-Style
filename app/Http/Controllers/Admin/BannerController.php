<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BannerRequest;
use App\Services\BannerService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class BannerController extends Controller implements HasMiddleware
{
    protected $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }
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
    public function store(BannerRequest $request)
    {
        $this->bannerService->createBanner($request->validated(), $request->file('image'));
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
    public function update(BannerRequest $request)
    {
        $banner = Banner::findOrFail($request->id);
        $this->bannerService->updateBanner($banner, $request->validated(), $request->file('image'));
        return redirect()->route('admin.banner.index')->with('success', 'Banner has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $this->bannerService->deleteBanner($banner);
        return redirect()->route('admin.banner.index')->with('success', 'Banner has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $this->bannerService->changeStatus($banner);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
