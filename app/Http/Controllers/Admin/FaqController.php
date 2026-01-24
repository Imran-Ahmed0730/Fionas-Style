<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Admin\Faq;
use App\Models\Admin\FaqCategory;
use App\Services\Admin\FaqService;

use App\Http\Requests\Admin\FaqRequest;

class FaqController extends Controller implements HasMiddleware
{
    protected $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    public static function middleware(): array
    {
        return [
                new Middleware('permission:FAQ Create', only: ['create', 'store']),
                new Middleware('permission:FAQ View', only: ['index']),
                new Middleware('permission:FAQ Update', only: ['edit', 'update']),
                new Middleware('permission:FAQ Delete', only: ['destroy']),
                new Middleware('permission:FAQ Status Change', only: ['changeStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = Faq::with('category')->latest()->get();
        return view('backend.faq.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['categories'] = FaqCategory::where('status', 1)->get();
        return view('backend.faq.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        $this->faqService->store($request->validated());

        return redirect()->route('admin.faq.index')->with('success', 'FAQ created successfully');
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
        $data['item'] = Faq::findOrFail($id);
        $data['categories'] = FaqCategory::where('status', 1)->get();
        return view('backend.faq.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request)
    {
        $faq = Faq::findOrFail($request->id);
        $this->faqService->update($faq, $request->validated());

        return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $faq = Faq::findOrFail($request->id);
        $this->faqService->destroy($faq);
        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully');
    }

    public function changeStatus($id)
    {
        $faq = Faq::findOrFail($id);
        $this->faqService->changeStatus($faq);
        return response()->json(['success' => true, 'message' => 'Status changed successfully']);
    }
}
