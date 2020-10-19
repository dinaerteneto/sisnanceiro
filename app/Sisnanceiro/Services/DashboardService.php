<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Repositories\BankInvoiceDetailRepository;

class DashboardService extends Service
{
 protected $bankInvoiceDetailRepository;

 public function __construct(BankInvoiceDetailRepository $bankInvoiceDetailRepository)
 {
  $this->bankInvoiceDetailRepository = $bankInvoiceDetailRepository;
 }

 public function ballance()
 {
  $mainCategories = [
   BankCategory::CATEGORY_INITIAL_BALANCE,
   BankCategory::CATEGORY_TO_PAY,
   BankCategory::CATEGORY_TO_RECEIVE,
   BankCategory::CATEGORY_CREDIT_INVOICE,
  ];

  $carbonStart = Carbon::now()->startOfMonth()->format('Y-m-d');
  $carbonEnd   = Carbon::now()->endOfMonth()->format('Y-m-d');

  $companyId = Auth::user()->company_id;
  $ballance  = \DB::table('bank_invoice_detail')
   ->selectRaw(\DB::raw('SUM(net_value) AS ballance'))
   ->join('bank_category', 'bank_category.id', '=', 'bank_invoice_detail.bank_category_id')
   ->whereBetween('due_date', [$carbonStart, $carbonEnd])
   ->where('bank_invoice_detail.status', BankInvoiceDetail::STATUS_PAID)
   ->where('bank_invoice_detail.company_id', '=', $companyId)
   ->whereNull('bank_invoice_detail.credit_card_id')
   ->whereNull('bank_invoice_detail.deleted_at');

  $bankAccount = clone $ballance;
  $toPay       = clone $ballance;
  $toReceive   = clone $ballance;
  $creditCard  = clone $ballance;

  $toPay->where('bank_category.main_parent_category_id', BankCategory::CATEGORY_TO_PAY);
  $toReceive->where('bank_category.main_parent_category_id', BankCategory::CATEGORY_TO_RECEIVE);
  $creditCard->where('bank_category.id', BankCategory::CATEGORY_CREDIT_INVOICE);

  $bankAccount = $bankAccount->first();
  $toPay       = $toPay->first();
  $toReceive   = $toReceive->first();
  $creditCard  = $creditCard->first();

  return compact('bankAccount', 'toPay', 'toReceive', 'creditCard');
 }

 /**
  * return total grouped by categories
  * @param array $mainParentCategoryIds ids of the categories
  * @param string $startDate initial date (YYYY-MM-DD)
  * @param string $endDate final date (YYYY-MM-DD)
  * @return array
  */
 public function totalByCategory($mainParentCategoryIds = [], $startDate, $endDate)
 {
  return $this->bankInvoiceDetailRepository->totalByCategory($mainParentCategoryIds, $startDate, $endDate);
 }

 /**
  * return total grouped by parent categories
  * @param array $mainParentCategoryIds ids of the categories
  * @param string $startDate initial date (YYYY-MM-DD)
  * @param string $endDate final date (YYYY-MM-DD)
  * @return array
  */
 public function totalByParentCategory($mainParentCategoryIds = [], $startDate, $endDate)
 {
  return $this->bankInvoiceDetailRepository->totalByParentCategory($mainParentCategoryIds, $startDate, $endDate);
 }

 /**
  * combine initial date and final date to find invoices
  * @param string $start inicial date to find invoices
  * @param string $end final date to find invoices
  * @return Collection
  */
 public function calendar($start, $end)
 {
  return $this->bankInvoiceDetailRepository->getByBetweenDueDate($start, $end);
 }

}
