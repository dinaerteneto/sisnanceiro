<?php
namespace Sisnanceiro\Repositories;

use Carbon\Carbon;
use Sisnanceiro\Models\CreditCard;

class CreditCardRepository extends Repository
{
 public function __construct(CreditCard $model)
 {
  $this->model = $model;
 }

 public function updateAllDueDate($model)
 {
  $dateCarbon = new Carbon('first day of this month');

  $sql = "
      UPDATE bank_invoice_detail bid
         SET bid.due_date = CONCAT(SUBSTRING(bid.due_date, 1, 8), '{$model->payment_day}')
           , bid.bank_account_id = {$model->bank_account_id}

       WHERE bid.deleted_at IS NULL
         AND bid.status = 1
         AND bid.due_date >= '{$dateCarbon->format('Y-m-d')}'
         AND bid.credit_card_id = {$model->id}
    ";

  return \DB::statement($sql);

 }

}
