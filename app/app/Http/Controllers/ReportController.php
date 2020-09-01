<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Services\BankAccountService;
use Sisnanceiro\Services\ReportService;
use Sisnanceiro\Transformers\CashFlowDetailTransformer;
use Sisnanceiro\Transformers\CashFlowTransformer;

class ReportController extends Controller
{

    public function __construct(ReportService $service, BankAccountService $bankAccountService)
    {
        $this->service            = $service;
        $this->bankAccountService = $bankAccountService;
    }

    public function cashFlow(Request $request)
    {
        $bankAccounts = BankAccount::all();
        if ($request->isMethod('post')) {

            $bankAccountIds = isset($request->get('extra_search')['bank_account_id']) ? $request->get('extra_search')['bank_account_id'] : [];
            if (count($bankAccountIds) <= 0) {
                //get all bankAccounts if not filtered by bank account
                $bankAccounts = $this->bankAccountService->all();
                if ($bankAccounts) {
                    foreach ($bankAccounts as $bankAccount) {
                        $bankAccountIds[] = $bankAccount->id;
                    }
                }
            }
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->get('extra_search')['start_date']);
            $dateTo   = Carbon::createFromFormat('Y-m-d', $request->get('extra_search')['end_date']);

            if ($dateTo->isPast()) {
                $records = $this->service->cashFlowPast($dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), $bankAccountIds);
            } elseif (!$dateFrom->isToday() && ($dateFrom->isPast() && $dateTo->isFuture())) {
                $records = $this->service->cashFlowPastAndFuture($dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), $bankAccountIds);
            } else {
                $records = $this->service->cashFlowFuture($dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), $bankAccountIds);
            }

            $dt = datatables()
                ->of($records)
                ->setTransformer(new CashFlowTransformer);
            return $dt->make(true);
        }
        return view('/reports/cash-flow', compact('bankAccounts'));
    }

    public function cashFlowDetail(Request $request)
    {
        $date           = $request->date;
        $bankAccountIds = null !== $request->get('bank_account_id') ? $request->get('bank_account_id') : [];
        if (count($bankAccountIds) <= 0) {
            //get all bankAccounts if not filtered by bank account
            $bankAccounts = $this->bankAccountService->all();
            if ($bankAccounts) {
                foreach ($bankAccounts as $bankAccount) {
                    $bankAccountIds[] = $bankAccount->id;
                }
            }
        }
        $records = $this->service->cashFlowPast($date, $date, $bankAccountIds, 'day');
        $data    = (object) fractal($records, new CashFlowDetailTransformer)->toArray()['data'];
        return view('/reports/_cash-flow-detail', compact('data'));
    }

}
