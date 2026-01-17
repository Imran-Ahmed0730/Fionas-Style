<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;
use App\Models\Admin\Attribute;
use App\Services\Admin\AttributeService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttributeController extends Controller implements HasMiddleware
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Attribute Add', only: ['create']),
            new Middleware('permission:Attribute View', only: ['index']),
            new Middleware('permission:Attribute Update', only: ['edit']),
            new Middleware('permission:Attribute Delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Attribute::latest()->get();
        return view('backend.attribute.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.attribute.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request)
    {
        $this->attributeService->createAttribute($request->validated());
        return redirect()->route('admin.attribute.index')->with('success', 'Attribute has been added successfully.');
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
        $data['item'] = Attribute::findOrFail($id);
        return view('backend.attribute.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request)
    {
        $attribute = Attribute::findOrFail($request->id);
        $this->attributeService->updateAttribute($attribute, $request->validated());
        return redirect()->route('admin.attribute.index')->with('success', 'Attribute has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $attribute = Attribute::findOrFail($request->id);
        $this->attributeService->deleteAttribute($attribute);
        return redirect()->route('admin.attribute.index')->with('success', 'Attribute has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $attribute = Attribute::findOrFail($id);
        $this->attributeService->changeStatus($attribute);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
