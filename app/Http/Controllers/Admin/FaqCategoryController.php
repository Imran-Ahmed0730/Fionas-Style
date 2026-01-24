<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin\FaqCategory;
use App\Services\Admin\FaqCategoryService;

use App\Http\Requests\Admin\FaqCategoryRequest;
use Laravel\Sanctum\Contracts\HasAbilities;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class FaqCategoryController extends Controller implements HasMiddleware
{
    protected $faqCategoryService;

    public function __construct(FaqCategoryService $faqCategoryService)
    {
        $this->faqCategoryService = $faqCategoryService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:FAQ Category Create', only: ['create', 'store']),
            new Middleware('permission:FAQ Category View', only: ['index']),
            new Middleware('permission:FAQ Category Update', only: ['edit', 'update']),
            new Middleware('permission:FAQ Category Delete', only: ['destroy']),
            new Middleware('permission:FAQ Category Status Change', only: ['changeStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = FaqCategory::where('id', '!=', 1)->latest()->get();
        return view('backend.faq.category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.faq.category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqCategoryRequest $request)
    {
        $this->faqCategoryService->store($request->validated());

        return redirect()->route('admin.faq.category.index')->with('success', 'FAQ Category created successfully');
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
        $data['item'] = FaqCategory::with('faqs')->where('id', '!=', 1)->findOrFail($id);
        return view('backend.faq.category.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqCategoryRequest $request)
    {
        $category = FaqCategory::where('id', '!=', 1)->findOrFail($request->id);
        $this->faqCategoryService->update($category, $request->validated());

        return redirect()->route('admin.faq.category.index')->with('success', 'FAQ Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $category = FaqCategory::where('id', '!=', 1)->findOrFail($request->id);
        $this->faqCategoryService->destroy($category);
        return redirect()->route('admin.faq.category.index')->with('success', 'FAQ Category deleted successfully');
    }

    public function changeStatus($id)
    {
        $category = FaqCategory::where('id', '!=', 1)->findOrFail($id);
        $this->faqCategoryService->changeStatus($category);
        return response()->json(['success' => true, 'message' => 'Status changed successfully']);
    }
}
