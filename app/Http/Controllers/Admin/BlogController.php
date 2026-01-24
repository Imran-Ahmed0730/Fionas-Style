<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use App\Services\Admin\BlogService;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Http\Requests\Admin\BlogRequest;

class BlogController extends Controller implements HasMiddleware
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Blog Create', only: ['create', 'store']),
            new Middleware('permission:Blog View', only: ['index']),
            new Middleware('permission:Blog Update', only: ['edit', 'update']),
            new Middleware('permission:Blog Delete', only: ['destroy']),
            new Middleware('permission:Blog Status Change', only: ['changeStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Blog::with('category')->latest()->get();
        return view('backend.blog.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['categories'] = BlogCategory::where('status', 1)->where('id', '!=', 1)->get();
        return view('backend.blog.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        $this->blogService->store($request->validated());

        return redirect()->route('admin.blog.index')->with('success', 'Blog created successfully');
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
        $data['item'] = Blog::findOrFail($id);
        $data['categories'] = BlogCategory::where('status', 1)->where('id', '!=', 1)->get();
        return view('backend.blog.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request)
    {
        $blog = Blog::findOrFail($request->id);
        $this->blogService->update($blog, $request->validated());

        return redirect()->route('admin.blog.index')->with('success', 'Blog updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $blog = Blog::findOrFail($request->id);
        $this->blogService->destroy($blog);
        return redirect()->route('admin.blog.index')->with('success', 'Blog deleted successfully');
    }

    public function changeStatus($id)
    {
        $blog = Blog::findOrFail($id);
        $this->blogService->changeStatus($blog);
        return response()->json(['success' => true, 'message' => 'Status changed successfully']);
    }
}
