<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeValueRequest;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use App\Services\Admin\AttributeValueService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttributeValueController extends Controller implements HasMiddleware
{
    protected $attributeValueService;

    public function __construct(AttributeValueService $attributeValueService)
    {
        $this->attributeValueService = $attributeValueService;
    }
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Attribute Value Add', only: ['store']),
            new Middleware('permission:Attribute Value View', only: ['index']),
            new Middleware('permission:Attribute Value Update', only: ['update']),
            new Middleware('permission:Attribute Value Delete', only: ['destroy']),
        ];
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeValueRequest $request)
    {
        $this->attributeValueService->createAttributeValue($request->validated());
        return back()->with('success', 'Attribute value has been added successfully.');
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
        return view('backend.attribute.value.index', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeValueRequest $request)
    {
        $attribute_value = AttributeValue::findOrFail($request->attribute_value_id);
        $this->attributeValueService->updateAttributeValue($attribute_value, $request->validated());
        return back()->with('success', 'Attribute value has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $attribute_value = AttributeValue::findOrFail($request->id);
        $this->attributeValueService->deleteAttributeValue($attribute_value);
        return back()->with('success', 'Attribute value has been deleted successfully.');
    }
}

