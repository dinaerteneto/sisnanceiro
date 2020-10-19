<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankCategory;

class BankAccountRepository extends Repository
{
 public function __construct(BankAccount $model)
 {
  $this->model = $model;
 }

 public function haveInvoice($id)
 {
  $query = \DB::table('bank_invoice_detail')
   ->whereNull('bank_invoice_detail.deleted_at')
   ->where('bank_category_id', '<>', BankCategory::CATEGORY_INITIAL_BALANCE)
   ->where('bank_account_id', '=', $id)
   ->first();

  return !!$query;
 }
}
