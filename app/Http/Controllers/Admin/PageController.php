<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PageController extends Controller implements HasMiddleware
{
    public static function middleware():array
    {
        return [
            new Middleware('permission:Page Create', only: ['create', 'store']),
            new Middleware('permission:Page View', only: ['index']),
            new Middleware('permission:Page Update', only: ['edit','update']),
            new Middleware('permission:Page Delete', only: ['destroy']),
            new Middleware('permission:Page Status Change', only: ['changeStatus']),
        ];
    }

    public function index()
    {
        $data['items'] = Page::latest()->cursor();
        return view('backend.page.index', $data);
    }

    public function create()
    {
        return view('backend.page.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required',
        ]);
        
        $metaImage = isset($request->meta_image) ? saveImagePath($request->meta_image, null, 'page') : null;
        $request->merge([
            'slug' => str()->slug($request->title).uniqid(),
            'meta_image' => $metaImage
        ]);
        Page::create([
            'title' => $request->title,
            'content' => $request->input('content'),
            'slug' => $request->slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_image' => $metaImage,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.page.index')->with('success', 'Page has been added successfully.');
    }

    public function edit($id)
    {
        $data['item'] = Page::findOrFail($id);
        return view('backend.page.form', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required',
        ]);
    
        $page = Page::findOrFail($request->id);

        if($page->title != $request->title){
            $page->merge([
                'slug' => str()->slug($request->title).uniqid(),
            ]);
        }
        $metaImage = $page->meta_image;
        if($request->file('meta_image') && $request->file('meta_image')->isValid()){
            $metaImage = saveImagePath($request->file('meta_image'), $page->meta_image, 'page');
        }

        $page->update([
            'title' => $request->title,
            'content' => $request->input('content'),
            'slug' => $page->slug,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,
            'meta_image' => $metaImage,
            'status' => $page->status,
        ]);
        return redirect()->route('admin.page.index')->with('success', 'Page has been updated successfully.');
    }

    public function destroy(Request $request)
    {
        $page = Page::findOrFail($request->id);
        $page->delete();
        return redirect()->route('admin.page.index')->with('success', 'Page has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $page = Page::findOrFail($id);
        $page->update([
            'status' => !$page->status,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Page status has been changed successfully.',
        ]);
    }
}
