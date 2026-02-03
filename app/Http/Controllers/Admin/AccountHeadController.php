<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AccountHead;
use App\Services\Admin\AccountHeadService;
use App\Http\Requests\Admin\AccountHeadRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AccountHeadController extends Controller implements HasMiddleware
{
    protected $accountHeadService;

    public function __construct(AccountHeadService $accountHeadService)
    {
        $this->accountHeadService = $accountHeadService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Account Head View', only: ['index']),
            new Middleware('permission:Account Head Add', only: ['store']),
            new Middleware('permission:Account Head Update', only: ['update']),
            new Middleware('permission:Account Head Delete', only: ['destroy']),
            new Middleware('permission:Account Head Status Change', only: ['status']),
        ];
    }

    public function index()
    {
        $heads = $this->accountHeadService->getAll();
        return view('backend.account-head.index', compact('heads'));
    }

    public function store(AccountHeadRequest $request)
    {
        $this->accountHeadService->store($request->only('title', 'type'));

        return redirect()->back()->with('success', 'Account Head created successfully');
    }

    public function update(AccountHeadRequest $request)
    {
        $accountHead = AccountHead::findOrFail($request->id);

        if ($accountHead->editable != 1) {
            return redirect()->back()->with('error', 'This Account Head is not editable.');
        }

        $this->accountHeadService->update($accountHead, $request->only('title', 'type'));

        return redirect()->back()->with('success', 'Account Head updated successfully');
    }

    public function destroy(Request $request)
    {
        $accountHead = AccountHead::findOrFail($request->id);

        if ($accountHead->editable != 1) {
            return redirect()->back()->with('error', 'This Account Head cannot be deleted.');
        }

        if ($this->accountHeadService->delete($accountHead)) {
            return redirect()->back()->with('success', 'Account Head deleted successfully');
        }

        return redirect()->back()->with('error', 'Standard Account Head cannot be deleted');
    }

    public function status($id)
    {
        $accountHead = AccountHead::findOrFail($id);

        if ($accountHead->editable != 1) {
            return response()->json(['success' => false, 'message' => 'Status change is not allowed for this Account Head.']);
        }

        $this->accountHeadService->changeStatus($accountHead);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}