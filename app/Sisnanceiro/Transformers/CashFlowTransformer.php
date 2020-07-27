<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;

class CashFlowTransformer extends TransformerAbstract
{
 public function transform($data)
 {
  $ret = [];
  if ($data) {
   $date = Carbon::createFromFormat('Y-m-d', $data->date);
   $ret  = [
    'date'                  => $date->format('d/m/Y'),
    'initial_balance'       => Mask::currency($data->initial_balance),
    'balance'               => Mask::currency($data->balance),
    'credit'                => Mask::currency($data->credit),
    'debit'                 => Mask::currency($data->debit),

    'initial_balance_value' => $data->initial_balance,
    'balance_value'         => $data->balance,
    'credit_value'          => $data->credit,
    'debit_value'           => $data->debit,
   ];
  }
  return $ret;
 }

}
