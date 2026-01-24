<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin\BlogCategory;
use App\Services\Admin\BlogCategoryService;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Http\Requests\Admin\BlogCategoryRequest;

class BlogCategoryController extends Controller implements HasMiddleware
{
    protected $blogCategoryService;

    public function __construct(BlogCategoryService $blogCategoryService)
    {
        $this->blogCategoryService = $blogCategoryService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Blog Category Create', only: ['create', 'store']),
            new Middleware('permission:Blog Category View', only: ['index']),
            new Middleware('permission:Blog Category Update', only: ['edit', 'update']),
            new Middleware('permission:Blog Category Delete', only: ['destroy']),
            new Middleware('permission:Blog Category Status Change', only: ['changeStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = BlogCategory::where('id', '!=', 1)->latest()->get();
        return view('backend.blog.category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.blog.category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryRequest $request)
    {
        $this->blogCategoryService->store($request->validated());

        return redirect()->route('admin.blog.category.index')->with('success', 'Blog Category created successfully');
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
        $data['item'] = BlogCategory::where('id', '!=', 1)->findOrFail($id);
        return view('backend.blog.category.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryRequest $request)
    {
        $category = BlogCategory::findOrFail($request->id);
        $this->blogCategoryService->update($category, $request->validated());

        return redirect()->route('admin.blog.category.index')->with('success', 'Blog Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->id == 1) {
            abort(403);
        }
        $category = BlogCategory::findOrFail($request->id);
        $this->blogCategoryService->destroy($category);
        return redirect()->route('admin.blog.category.index')->with('success', 'Blog Category deleted successfully');
    }

    public function changeStatus($id)
    {
        if ($id == 1) {
            abort(403);
        }
        $category = BlogCategory::findOrFail($id);
        $this->blogCategoryService->changeStatus($category);
        return response()->json(['success' => true, 'message' => 'Status changed successfully']);
    }
}
