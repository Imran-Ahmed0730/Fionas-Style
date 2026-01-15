<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class CategoryController extends Controller implements HasMiddleware
{
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
    public function store(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg',
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        $icon = saveImagePath($request->file('icon'), null, 'category/icon');
        if($request->file('cover_photo')){
            $cover_photo = saveImagePath($request->file('cover_photo'), null,'category/cover-photo');
        }
        if($request->parent_id != 0){
            $parent_category = Category::where('id', $request->parent_id)->first();
            $level = $parent_category->level + 1;
        }
        else{
            $level = 1;
        }
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'icon' => $icon,
            'level' => $level,
            'parent_id' => $request->parent_id,
            'cover_photo' => $cover_photo,
            'priority' => $request->priority,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_image' => saveImagePath($request->file('meta_image'), null, 'category/meta-image'),
        ]); 
        
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
    public function update(Request $request)
    {
//        return $request;
        $request->validate([
            'name' => 'required|unique:brands,name',
            'description' => 'required',
            'icon' => 'image|mimes:jpeg,png,jpg',
            'cover_photo' => 'image|mimes:jpeg,png,jpg',
        ]);
        $category = Category::findOrFail($request->id);
        if($category->name != $request->name){
            $request->validate([
                'name' => 'unique:categories,name'
            ]);
        }
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $icon = saveImagePath($icon, $category->icon, 'category/icon' );
        }
        else{
            $icon = $category->icon;
        }

        if ($request->hasFile('cover_photo')) {
            $cover_photo = $request->file('cover_photo');
            $cover_photo = saveImagePath($cover_photo, $category->cover_photo, 'category/cover-photo' );
        }
        else{
            $cover_photo = $category->cover_photo;
        }
        if($request->parent_id != 0){
            $parent_category = Category::where('id', $request->parent_id)->first();
            $level = $parent_category->level + 1;
        }
        else{
            $level = 1;
        }
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'level' => $level,
            'parent_id' => $request->parent_id,
            'icon' => $icon,
            'cover_photo' => $cover_photo,
            'priority' => $request->priority,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_image' => saveImagePath($request->file('meta_image'), $category->meta_image, 'category/meta-image'),
        ]); 
        
        return redirect()->route('admin.category.index')->with('success', 'Category has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if ($category->icon && file_exists($category->icon)) {
            unlink($category->icon);
        }
        if ($category->cover_photo && file_exists($category->cover_photo)) {
            unlink($category->cover_photo);
        }
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Category has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $category = Category::findOrFail($id);
        $status = 1;
        if($category->status == 1){
            $status = 0;
        }
        $category->update([
            'status' => $status,
        ]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
    public function homeInclude($id)
    {
        $category = Category::findOrFail($id);
        $home_include = 1;
        if($category->included_to_home == 1){
            $home_include = 0;
        }
        $category->update([
            'included_to_home' => $home_include,
        ]);
        return response()->json(['success' => true, 'message' => 'Home inclusion updated successfully.']);
    }
}
