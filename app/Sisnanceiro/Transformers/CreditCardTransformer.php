<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;

class CreditCardTransformer extends TransformerAbstract
{
    public function transform($creditCard)
    {
        $availableLimit = 15029.42;
        $partialValue   = 5470;
        $closesIn       = Carbon::createFromFormat('Y-m-d', date("Y-m") . "-{$creditCard->closing_day}");
        if ($closesIn->isPast()) {
            $closesIn = $closesIn->addMonth();
        }
        $totalPercent = ($partialValue / $creditCard->limit) * 100;

        return [
            'id'             => $creditCard->id,
            'brand_name'     => $creditCard->creditCardBrand->name,
            'name'           => $creditCard->name,
            'limit'          => Mask::currency($creditCard->limit),
            'closesIn'       => $closesIn->format('d/m/Y'),
            'partialValue'   => Mask::currency($partialValue),
            'totalPercent'   => Mask::currency($totalPercent),
            'availableLimit' => Mask::currency($availableLimit),
        ];
    }
}
