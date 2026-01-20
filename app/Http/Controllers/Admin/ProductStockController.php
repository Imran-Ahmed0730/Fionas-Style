<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStockRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Admin\ProductStock;
use App\Services\ProductStockService;
use App\Models\Admin\Product;
use App\Models\Admin\Supplier;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\ProductVariant;

class ProductStockController extends Controller implements HasMiddleware
{
    private $productStockService;

    public function __construct(ProductStockService $productStockService)
    {
        $this->productStockService = $productStockService;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Product Stock Add', only: ['create']),
            new Middleware('permission:Product Stock View', only: ['index']),
            new Middleware('permission:Product Stock Update', only: ['edit']),
            new Middleware('permission:Product Stock Delete', only: ['destroy']),
            new Middleware('permission:Product Stock Transfer', only: ['transfer']),
            new Middleware('permission:Product Stock Re-assign', only: ['edit']),

        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = ProductStock::with(['product', 'variant', 'supplier', 'addedBy'])->latest();
        if (isset($_GET['product_id']) && $_GET['product_id'] != '') {
            $stocks = $stocks->where('product_name', function ($query) {
                $query->select('name')->from('products')->where('id', $_GET['product_id']);
            });
        }
        if (isset($_GET['stock_sku']) && $_GET['stock_sku'] != '') {
            $stocks = $stocks->where('sku', $_GET['stock_sku']);
        }
        $data['items'] = $stocks->get()->groupBy('sku');
        $data['products'] = Product::orderBy('name', 'asc')->get();
        $data['stock_skus'] = ProductStock::select('sku')->distinct()->get();

        return view('backend.product.stock.index', $data);
    }

    public function generateSku()
    {
        return response()->json([
            'sku' => $this->productStockService->generateSku()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] = Product::orderBy('name', 'asc')->get();
        $data['suppliers'] = Supplier::active()->orderBy('name', 'asc')->get();
        return view('backend.product.stock.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStockRequest $request)
    {
        $product = Product::findOrFail($request->product_id);
        $this->productStockService->create($product, $request->validated());
        return redirect()->route('admin.stock.index')->with('success', 'Product stock added successfully!');
    }

    public function getProduct(Request $request)
    {
        $product = Product::with('variants')->findOrFail($request->productId);
        return response()->json([
            'name' => $product->name,
            'buying_price' => $product->buying_price,
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'name' => $variant->name,
                    'sku' => $variant->sku,
                ];
            })
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        DB::transaction(function () use ($request) {
            $item = ProductStock::findOrFail($request->id);

            // Decrement total product stock
            if ($item->product) {
                $item->product->decrement('stock_qty', $item->qty);
            }

            // Decrement variant stock if applicable
            if ($item->variant_sku) {
                $variant = ProductVariant::where('sku', $item->variant_sku)
                    ->whereHas('product', function ($query) use ($item) {
                        $query->where('name', $item->product_name);
                    })->first();

                if ($variant) {
                    $variant->decrement('stock_qty', $item->qty);
                }
            }

            $item->delete();
        });

        return redirect()->route('admin.stock.index')->with('success', 'Product stock deleted successfully!');
    }
}
