<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;

class CreditCardTransformer extends TransformerAbstract
{

 public function transform($creditCard)
 {

  $closesIn = Carbon::createFromFormat('Y-m-d', date("Y-m") . "-{$creditCard->closing_day}");
  if ($closesIn->isPast()) {
   $closesIn = $closesIn->addMonth(1);
  }
  $closeInPast = clone $closesIn;

  $startDate = $closeInPast->addMonth(-1)->format('Y-m-d');
  $endDate   = $closesIn->format('Y-m-d');

  $total = $creditCard->getTotal($creditCard->id, $startDate, $endDate);
  $total = isset($total) ? $total->total : 0;

  $availableLimit = $creditCard->limit + $total;
  $partialValue   = $total * -1;
  $totalPercent   = ($partialValue / $creditCard->limit) * 100;

  return [
   'id'               => $creditCard->id,
   'brand_name'       => $creditCard->creditCardBrand->name,
   'name'             => $creditCard->name,
   'limit'            => Mask::currency($creditCard->limit),
   'closesIn'         => $closesIn->format('d/m/Y'),
   'maskPartialValue' => Mask::currency($partialValue),
   'partialValue'     => $partialValue,
   'maskTotalPercent' => Mask::currency($totalPercent),
   'totalPercent'     => $totalPercent,
   'availableLimit'   => Mask::currency($availableLimit),
  ];
 }
}
