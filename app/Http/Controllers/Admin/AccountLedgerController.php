<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AccountLedgerService;
use App\Http\Requests\Admin\AccountLedgerRequest;
use App\Models\Admin\AccountHead;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;

class AccountLedgerController extends Controller implements HasMiddleware
{
    protected $ledgerService;

    public function __construct(AccountLedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Account Balance Sheet View', only: ['balanceSheet']),
            new Middleware('permission:Account Cashbook View', only: ['cashbook']),
            new Middleware('permission:Sales Report View', only: ['salesReport']),
            new Middleware('permission:Account Ledger Add', only: ['create', 'store']),
        ];
    }

    public function create()
    {
        $data['accountHeads'] = AccountHead::active()->get();
        return view('backend.account-ledger.form', $data);
    }

    public function store(AccountLedgerRequest $request)
    {
        try {
            $this->ledgerService->recordTransaction($request->validated());
            return redirect()->route('admin.account-report.balance-sheet')->with('success', 'Transaction recorded successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function balanceSheet(AccountLedgerRequest $request)
    {
        $data['startDate'] = $request->get('start_date');
        $data['endDate'] = $request->get('end_date');
        $data['ledgers'] = $this->ledgerService->getBalanceSheet($data['startDate'], $data['endDate']);

        return view('backend.account-report.balance-sheet', $data);
    }

    public function cashbook(AccountLedgerRequest $request)
    {
        $data['date'] = $request->get('date');
        $data['ledgers'] = $this->ledgerService->getCashbook($data['date']);

        return view('backend.account-report.cashbook', $data);
    }

    public function salesReport(AccountLedgerRequest $request)
    {
        $data['startDate'] = $request->get('start_date');
        $data['endDate'] = $request->get('end_date');
        $data['ledgers'] = $this->ledgerService->getSalesReport($data['startDate'], $data['endDate']);

        return view('backend.account-report.sales-report', $data);
    }
}
