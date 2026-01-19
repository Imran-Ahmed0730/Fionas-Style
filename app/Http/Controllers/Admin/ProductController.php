<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\ProductImage;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware
{
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Product View', only: ['index']),
            new Middleware('permission:Product Details', only: ['show']),
            new Middleware('permission:Product Add', only: ['create', 'store']),
            new Middleware('permission:Product Update', only: ['edit', 'update']),
            new Middleware('permission:Product Delete', only: ['destroy']),
            new Middleware('permission:Product Status Change', only: ['changeStatus']),
            new Middleware('permission:Product Featured Status Change', only: ['changeFeaturedStatus']),
            new Middleware('permission:Product Todays Deal Status Change', only: ['changeTodaysDealStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Product::latest()->get();
        return view('backend.product.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->productService->getProductAttributes();
        return view('backend.product.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $this->productService->create($request->validated());
        return redirect()->route('admin.product.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['item'] = $this->productService->getById($id);
        return view('backend.product.details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['item'] = $this->productService->getById($id);

        $data = array_merge(
            $data,
            $this->productService->getProductAttributes()
        );
        return view('backend.product.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request)
    {
//        dd($request->all());
        $product = $this->productService->getById($request->id);
        $this->productService->update($product, $request->validated());

        return redirect()->route('admin.product.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->productService->delete($request->id);

        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    public function changeStatus(string $id)
    {
        $product = $this->productService->getById($id);
        $this->productService->changeStatus($product, 'status');

        return response()->json([
            'status' => 'success',
            'message' => 'Product status changed successfully'
        ]);
    }

    public function changeFeaturedStatus(string $id)
    {
        $product = $this->productService->getById($id);
        $this->productService->changeStatus($product, 'is_featured');

        return response()->json([
            'status' => 'success',
            'message' => 'Product featured status changed successfully'
        ]);
    }

    public function changeTodaysDealStatus(string $id)
    {
        $product = $this->productService->getById($id);
        $this->productService->changeStatus($product, 'include_to_todays_deal');

        return response()->json([
            'status' => 'success',
            'message' => 'Product todays deal status changed successfully'
        ]);
    }

    public function getAttributeValues(Request $request)
    {
        $attribute_ids = $request->attribute_id;
        $attributeValues = $this->productService->getAttributeValues($attribute_ids);
        return response()->json($attributeValues);
    }

    public function deleteImage(Request $request)
    {
        $productImage = ProductImage::findOrFail($request->id);
        $this->productService->deleteImage($productImage);
        return response()->json([
            'status' => 'success',
            'message' => 'Product image deleted successfully'
        ]);
    }

    public function deleteVariant(Request $request)
    {
        $variant = ProductVariant::findOrFail($request->id);
        $this->productService->deleteVariant($variant);
        return response()->json([
            'status' => 'success',
            'message' => 'Product variant deleted successfully'
        ]);
    }

    public function generateSku()
    {
        $sku = $this->productService->generateSku();
        // Return the unique SKU in the response
        return response()->json(['sku' => $sku]);
    }

}
