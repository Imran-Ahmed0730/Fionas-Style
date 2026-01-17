<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Admin\Page;
use App\Services\Admin\PageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PageController extends Controller implements HasMiddleware
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }
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
        $data['items'] = Page::latest()->get();
        return view('backend.page.index', $data);
    }

    public function create()
    {
        return view('backend.page.form');
    }

    public function store(PageRequest $request)
    {
        $this->pageService->createPage($request->validated(), $request->file('meta_image'));
        return redirect()->route('admin.page.index')->with('success', 'Page has been added successfully.');
    }

    public function edit($id)
    {
        $data['item'] = Page::findOrFail($id);
        return view('backend.page.form', $data);
    }

    public function update(PageRequest $request)
    {
        $page = Page::findOrFail($request->id);
        $this->pageService->updatePage($page, $request->validated(), $request->file('meta_image'));
        return redirect()->route('admin.page.index')->with('success', 'Page has been updated successfully.');
    }

    public function destroy(Request $request)
    {
        $page = Page::findOrFail($request->id);
        $this->pageService->deletePage($page);
        return redirect()->route('admin.page.index')->with('success', 'Page has been deleted successfully.');
    }

    public function changeStatus($id)
    {
        $page = Page::findOrFail($id);
        $this->pageService->changeStatus($page);
        return response()->json([
            'success' => true,
            'message' => 'Page status has been changed successfully.',
        ]);
    }
}
