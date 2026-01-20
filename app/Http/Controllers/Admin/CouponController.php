<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\Admin\CouponService;
use App\Models\Admin\Product;
use App\Models\Admin\Customer;

class CouponController extends Controller implements HasMiddleware
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Coupon Add', only: ['create', 'store']),
            new Middleware('permission:Coupon View', only: ['index']),
            new Middleware('permission:Coupon Details', only: ['show']),
            new Middleware('permission:Coupon Update', only: ['edit', 'update']),
            new Middleware('permission:Coupon Delete', only: ['destroy']),
            new Middleware('permission:Coupon Status Change', only: ['changeStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Coupon::latest()->get();
        return view('backend.coupon.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] = Product::where('status', 1)->orderBy('name', 'asc')->get();
        $data['customers'] = Customer::where('status', 1)->whereHas('user', function($query){
            $query->orderBy('name', 'asc');
        })->get();
        return view('backend.coupon.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        $this->couponService->store($request->validated());
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['item'] = Coupon::findOrFail($id);
        return view('backend.coupon.details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['item'] = Coupon::findOrFail($id);
        return view('backend.coupon.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        $this->couponService->update($coupon, $request->validated());
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->couponService->destroy($coupon);
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon deleted successfully');
    }

    public function changeStatus(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->couponService->changeStatus($coupon);
        return response()->json([
            'success' => true,
            'message' => 'Coupon status updated successfully'
        ]);
    }
}
