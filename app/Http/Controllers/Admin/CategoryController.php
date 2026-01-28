<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Admin\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Category Add', only: ['create']),
            new Middleware('permission:Category View', only: ['index']),
            new Middleware('permission:Category Update', only: ['edit']),
            new Middleware('permission:Category Delete', only: ['destroy']),
            new Middleware('permission:Category Status Change', only: ['changeStatus']),
            new Middleware('permission:Category Include to Home', only: ['homeInclude']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Category::where('id', '!=', 1)->latest()->get();
        return view('backend.category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $max_level = getSetting('max_category_level') - 1;
        $data['categories'] = Category::where('level', '<=', $max_level)->where('id', '!=', 1)->get();
        return view('backend.category.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryService->createCategory(
            $request->validated(),
            $request->file('icon'),
            $request->file('cover_photo'),
            $request->file('meta_image')
        );
        return redirect()->route('admin.category.index')->with('success', 'Category has been added successfully.');
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
        $data['item'] = Category::findOrFail($id);
        $max_level = getSetting('max_category_level') - 1;
        $data['categories'] = Category::where('level', '<=', $max_level)->where('id', '!=', 1)->where('id', '!=', $id)->get();
        return view('backend.category.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request)
    {
        $category = Category::findOrFail($request->id);
        $this->categoryService->updateCategory(
            $category,
            $request->validated(),
            $request->file('icon'),
            $request->file('cover_photo'),
            $request->file('meta_image')
        );
        return redirect()->route('admin.category.index')->with('success', 'Category has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $this->categoryService->deleteCategory($category);
        return redirect()->route('admin.category.index')->with('success', 'Category has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryService->changeStatus($category);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
    public function homeInclude($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryService->homeInclude($category);
        return response()->json(['success' => true, 'message' => 'Home inclusion updated successfully.']);
    }
    public function featuredInclude($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryService->featuredInclude($category);
        return response()->json(['success' => true, 'message' => 'Featured inclusion updated successfully.']);
    }
}
