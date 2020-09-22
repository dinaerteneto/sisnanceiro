<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\BankInvoiceDetail;

class DashboardCalendarTransformer extends TransformerAbstract
{
 public function transform(BankInvoiceDetail $invoice)
 {
  $carbonDueDate = Carbon::createFromFormat('Y-m-d', $invoice->due_date);
  $netValue      = Mask::currency($invoice->net_value < 0 ? $invoice->net_value * -1 : $invoice->net_value);
  $bankCategory  = $invoice->category->name;
  $transaction   = $invoice->transaction->description;
  $description   = "{$bankCategory} <br /> {$transaction}";

  return [
   'id'            => $invoice->id,
   'start_date'    => $carbonDueDate->format('Y-m-d'),
   'end_date'      => $carbonDueDate->format('Y-m-d'),
   'start_date_BR' => $carbonDueDate->format('d/m/Y'),
   'end_date_BR'   => $carbonDueDate->format('d/m/Y'),
   'net_value'     => $netValue,
   'className'     => $invoice->net_value < 0 ? ["event", "bg-color-red"] : ["event", "bg-color-blue"],
   'description'   => $description,
   'name'          => "R$ {$netValue}",
  ];

 }
}
