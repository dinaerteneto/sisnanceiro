<?php

namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;

class PaymentTaxTransformer extends TransformerAbstract
{
    public function transform($paymentTax)
    {

        return [
            'id'                => $paymentTax->id,
            'bank_account_id'   => $paymentTax->bank_account_id,
            'payment_method_id' => $paymentTax->payment_method_id,
            'name'              => $paymentTax->name,
            'bank_account_name' => $paymentTax->bankAccount->name,
            'days_for_payment'  => $paymentTax->days_for_payment,
            'business_day'      => $paymentTax->days_business,
        ];
    }
}
