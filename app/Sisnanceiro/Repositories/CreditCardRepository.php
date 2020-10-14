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

 public function updateAllDueDate($creditCardId, $dueDay)
 {
  $dateCarbon = new Carbon('first day of this month');

  $sql = "
      UPDATE bank_invoice_detail bid
        JOIN bank_invoice_transaction bit2
          ON bit2.id = bid.bank_invoice_transaction_id

         SET bid.due_date = CONCAT(SUBSTRING(bid.due_date, 1, 8), '{$dueDay}')

       WHERE bid.deleted_at IS NULL
         AND bit2.is_credit_card_invoice = 1
         AND bid.status = 1
         AND bid.due_date >= '{$dateCarbon->format('Y-m-d')}'
         AND bit2.credit_card_id = {$creditCardId}
    ";

  return \DB::statement($sql);
 }

}
