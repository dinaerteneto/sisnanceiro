<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;

class BankTransactionTransferTransformer extends TransformerAbstract
{
    public function transform($bankInvoiceDetail)
    {
        $dueDateCarbon     = Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->due_date);
        $paymentDateCarbon = !empty($bankInvoiceDetail->payment_date) ? Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->payment_date) : null;
        $netValue          = Mask::currency($bankInvoiceDetail->net_value < 0 ? $bankInvoiceDetail->net_value * -1 : $bankInvoiceDetail->net_value);
        $transaction       = isset($bankInvoiceDetail->transaction) ? $bankInvoiceDetail->transaction : $bankInvoiceDetail;
        $accountSource     = $bankInvoiceDetail->bank_account_source_name;
        $accountTarget     = $bankInvoiceDetail->bank_account_target_name;
        $description       = $bankInvoiceDetail->description;

        return [
            'id'                          => $bankInvoiceDetail->id,
            'bank_invoice_transaction_id' => $bankInvoiceDetail->bank_invoice_transaction_id,
            'due_date'                    => $dueDateCarbon->format('d/m/Y'),
            'payment_date'                => !empty($paymentDateCarbon) ? $paymentDateCarbon->format('d/m/Y') : null,
            'description'                 => $description,
            'account_name_source'         => $accountSource,
            'account_name_target'         => $accountTarget,
            'net_value'                   => $netValue,
            'net_value_original'          => $bankInvoiceDetail->net_value,
        ];
    }
}
