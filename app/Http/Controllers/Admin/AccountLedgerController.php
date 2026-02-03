<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AccountLedgerService;
use App\Http\Requests\Admin\AccountLedgerRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
        ];
    }

    public function balanceSheet(AccountLedgerRequest $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $ledgers = $this->ledgerService->getBalanceSheet($startDate, $endDate);

        return view('backend.account-report.balance-sheet', compact('ledgers', 'startDate', 'endDate'));
    }

    public function cashbook(AccountLedgerRequest $request)
    {
        $date = $request->get('date');
        $ledgers = $this->ledgerService->getCashbook($date);

        return view('backend.account-report.cashbook', compact('ledgers', 'date'));
    }

    public function salesReport(AccountLedgerRequest $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $ledgers = $this->ledgerService->getSalesReport($startDate, $endDate);

        return view('backend.account-report.sales-report', compact('ledgers', 'startDate', 'endDate'));
    }
}
