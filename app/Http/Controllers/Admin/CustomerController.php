<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\Admin\Customer;
use App\Services\Admin\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CustomerController extends Controller implements HasMiddleware
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Customer Add', only: ['create', 'store']),
            new Middleware('permission:Customer View', only: ['index']),
            new Middleware('permission:Customer Update', only: ['edit', 'update']),
            new Middleware('permission:Customer Delete', only: ['destroy']),
            new Middleware('permission:Customer Status Change', only: ['changeStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Customer::latest()->get();
        return view('backend.customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return customer data for modal (used for edit)
        if (request()->ajax() && request()->has('id')) {
            $customer = Customer::findOrFail(request()->id);
            return response()->json(['success' => true, 'data' => $customer]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid request']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        try {
            $this->customerService->store($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['item'] = Customer::findOrFail($id);
        return view('backend.customer.details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (request()->ajax()) {
            $customer = Customer::with('user')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $customer]);
        }

        $data['item'] = Customer::with('user')->findOrFail($id);
        return view('backend.customer.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $customer = Customer::findOrFail($request->id);
            $this->customerService->update($customer, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $customer = Customer::findOrFail($request->id);
            $this->customerService->destroy($customer);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer deleted successfully'
                ]);
            }

            return redirect()->route('admin.customer.index')->with('success', 'Customer deleted successfully');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete customer: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete customer');
        }
    }

    public function changeStatus(string $id)
    {
        $customer = Customer::findOrFail($id);
        $this->customerService->changeStatus($customer);
        return response()->json([
            'success' => true,
            'message' => 'Customer status updated successfully'
        ]);
    }
}

