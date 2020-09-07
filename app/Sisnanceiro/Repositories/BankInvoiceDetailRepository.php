<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;

class BankInvoiceDetailRepository extends Repository
{
 public function __construct(BankInvoiceDetail $model)
 {
  $this->model = $model;
 }

 /**
  * return BankInvoice of the inicial balance
  * @param BankAccount $bankAccount
  * @return BankInvoiceDetail
  */
 public function findInitialBalance(BankAccount $bankAccount)
 {
  return $this->model
   ->where('bank_account_id', '=', $bankAccount->id)
   ->where('bank_category_id', '=', BankCategory::CATEGORY_INITIAL_BALANCE)
   ->first();
 }

 public function findCreditCardByDueDate($creditCardId, $dueDate)
 {
  $companyId = \Auth::user()->company_id;
  $query     = \DB::query()
   ->selectRaw("
                bank_invoice_detail.*
            ")
   ->from('bank_invoice_detail')
   ->join('bank_invoice_transaction', \DB::raw('bank_invoice_detail.bank_invoice_transaction_id'), '=', \DB::raw('bank_invoice_transaction.id'))
   ->where('bank_invoice_detail.company_id', $companyId)
   ->where('bank_invoice_transaction.credit_card_id', $creditCardId)
   ->where('bank_invoice_transaction.is_credit_card_invoice', true)
   ->where('bank_invoice_transaction.credit_card_due_date', $dueDate)
   ->whereNull('bank_invoice_detail.deleted_at');
  return $query->first();
 }

 public function creditCardInvoiceUpdateValues($creditCardId, $dueDate)
 {
// sum all invoices based due date and credit card id
  $companyId = \Auth::user()->company_id;
  $query     = \DB::query()
   ->selectRaw("SUM(bank_invoice_detail.net_value) AS net_value")
   ->from('bank_invoice_detail')
   ->where('bank_invoice_detail.company_id', '=', $companyId)
   ->where('bank_invoice_detail.credit_card_id', '=', $creditCardId)
   ->where('bank_invoice_detail.due_date', '=', $dueDate)
   ->whereNull('bank_invoice_detail.deleted_at')
   ->first();
  if ($query) {
   $netValue = $query->net_value;
//get invoice credit card to update net value
   $invoice = $this->findCreditCardByDueDate($creditCardId, $dueDate);
   $this->model->fill(['id' => $invoice->id, 'net_value' => $netValue, 'gross_value' => $netValue]);
   return $this->model->save();
  }

  return false;
 }

}
