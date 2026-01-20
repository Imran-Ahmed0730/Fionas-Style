<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Models\Admin\Campaign;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Services\Admin\CampaignService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CampaignController extends Controller implements HasMiddleware
{
    private $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Campaign Add', only: ['create', 'store']),
            new Middleware('permission:Campaign View', only: ['index']),
            new Middleware('permission:Campaign Update', only: ['edit', 'update']),
            new Middleware('permission:Campaign Delete', only: ['destroy']),
            new Middleware('permission:Campaign Status Change', only: ['changeStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Campaign::latest()->get();
        return view('backend.campaign.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['categories'] = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $data['products'] = Product::where('status', 1)->orderBy('name', 'asc')->get();
        return view('backend.campaign.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignRequest $request)
    {
        $this->campaignService->store($request->validated());
        return redirect()->route('admin.campaign.index')->with('success', 'Campaign created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['item'] = Campaign::findOrFail($id);
        return view('backend.campaign.details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['item'] = Campaign::findOrFail($id);
        $data['categories'] = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $data['products'] = Product::where('status', 1)->orderBy('name', 'asc')->get();
        return view('backend.campaign.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $campaign = Campaign::findOrFail($request->id);
        $this->campaignService->update($campaign, $request->all());
        return redirect()->route('admin.campaign.index')->with('success', 'Campaign updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $campaign = Campaign::findOrFail($request->id);
        $this->campaignService->destroy($campaign);
        return redirect()->route('admin.campaign.index')->with('success', 'Campaign deleted successfully');
    }

    public function changeStatus(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $this->campaignService->changeStatus($campaign);
        return response()->json([
            'success' => true,
            'message' => 'Campaign status updated successfully'
        ]);
    }
}

