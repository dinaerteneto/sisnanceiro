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

 public function destroyCreditCardOrphanInvoice()
 {

  $creditCardCategory = BankCategory::CATEGORY_CREDIT_CARD_BALANCE;

  $sql = "
      SELECT *
      FROM (
      SELECT (
      SELECT COUNT(id)
        FROM bank_invoice_detail bid2
        WHERE bid2.due_date = bit2.credit_card_due_date
          AND bid2.bank_category_id NOT IN ({$creditCardCategory})
          AND deleted_at IS NULL
      ) AS total_invoices
      , bit2.credit_card_due_date
      , bit2.id
        FROM bank_invoice_transaction bit2
      WHERE bit2.is_credit_card_invoice = TRUE
    ) AS BASE
    WHERE BASE.total_invoices = 0
    ";
  $query = \DB::select($sql);
  if ($query) {
   foreach ($query as $record) {
    $invoice     = $this->findBy('bank_invoice_transaction_id', $record->id);
    $transaction = $invoice->transaction()->first();
    $invoice->delete();
    $transaction->delete();
   }
  }
  return true;
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

  $companyId                 = \Auth::user()->company_id;
  $mainParentCategoryIds     = implode($mainParentCategoryIds);
  $bankCategoryCreditInvoice = BankCategory::CATEGORY_CREDIT_INVOICE;

  $sql = "
    SELECT SUM(net_value) AS total
         , bc.id
         , bc.name
      FROM bank_invoice_detail bid
      JOIN bank_category bc
        ON bid.bank_category_id = bc.id
     WHERE bid.deleted_at is null
       AND bid.due_date between '{$startDate}' AND '{$endDate}'
       AND bid.company_id = {$companyId}
       AND bc.main_parent_category_id in ({$mainParentCategoryIds})
       AND bid.bank_category_id NOT IN ({$bankCategoryCreditInvoice})
     GROUP BY bc.id, bc.name
     ORDER BY bc.name;
    ";

  $query = \DB::select($sql);

  return $query;
 }

 /**
  * return total grouped by main categories
  * @param array $mainParentCategoryIds ids of the categories
  * @param string $startDate initial date (YYYY-MM-DD)
  * @param string $endDate final date (YYYY-MM-DD)
  * @return array
  */
 public function totalByParentCategory($mainParentCategoryIds = [], $startDate, $endDate)
 {

  $companyId                 = \Auth::user()->company_id;
  $mainParentCategoryIds     = implode($mainParentCategoryIds);
  $bankCategoryCreditInvoice = BankCategory::CATEGORY_CREDIT_INVOICE;

  $sql = "
    SELECT SUM(net_value) AS total
         , bc2.id
         , case when bc.parent_category_id <=9 then bc.name else bc2.name end name
      FROM bank_invoice_detail bid
      JOIN bank_category bc
        ON bid.bank_category_id = bc.id
      JOIN bank_category bc2
        ON bc.parent_category_id = bc2.id
     WHERE bid.deleted_at is null
       AND bid.due_date between '{$startDate}' AND '{$endDate}'
       AND bid.company_id = {$companyId}
       AND bc.main_parent_category_id in ({$mainParentCategoryIds})
       AND bid.bank_category_id NOT IN ({$bankCategoryCreditInvoice})
     GROUP BY name
     ORDER BY bc2.name;
    ";

  $query = \DB::select($sql);

  return $query;
 }

 public function getByBetweenDueDate($start, $end)
 {
  $dateBetween = [$start, $end];
  return $this->model
   ->whereBetween('due_date', $dateBetween)
   ->orWhereBetween('competence_date', $dateBetween)
   ->orWhereBetween('payment_date', $dateBetween)
   ->orWhereBetween('receive_date', $dateBetween)
   ->get();
 }

}
