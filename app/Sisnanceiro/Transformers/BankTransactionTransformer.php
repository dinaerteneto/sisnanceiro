<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;

class BankTransactionTransformer extends TransformerAbstract
{
    public function transform(BankInvoiceDetail $bankInvoiceDetail)
    {
        $status        = BankInvoiceDetail::getStatus($bankInvoiceDetail->status);
        $dueDateCarbon = Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->due_date);
        $netValue      = Mask::currency($bankInvoiceDetail->net_value);

        $dued = false;
        if ($bankInvoiceDetail->category->main_parent_category_id == BankCategory::CATEGORY_TO_PAY) {
            if (empty($bankInvoiceDetail->payment_date) && $dueDateCarbon->isPast()) {
                $dued = true;
            }
        }
        if ($bankInvoiceDetail->category->main_parent_category_id == BankCategory::CATEGORY_TO_RECEIVE) {
            if (empty($bankInvoiceDetail->receive_date) && $dueDateCarbon->isPast()) {
                $dued = true;
            }
        }

        if ($dued) {
            $status = 'red';
        }
        if ($bankInvoiceDetail->status == BankInvoiceDetail::STATUS_PAID) {
            $status = 'green';
        }

        return [
            'id'                          => $bankInvoiceDetail->id,
            'bank_invoice_transaction_id' => $bankInvoiceDetail->bank_invoice_transaction_id,
            'bank_account_id'             => $bankInvoiceDetail->bank_account_id,
            'bank_category_id'            => $bankInvoiceDetail->bank_category_id,
            'main_category_id'            => $bankInvoiceDetail->category->main_parent_category_id,
            'status'                      => $bankInvoiceDetail->status,
            'label_status'                => $status,
            'due_date'                    => $dueDateCarbon->format('d/m/Y'),
            'description'                 => $bankInvoiceDetail->transaction->description,
            'category_name'               => $bankInvoiceDetail->category->name,
            'account_name'                => $bankInvoiceDetail->account->name,
            'parcel_number'               => $bankInvoiceDetail->parcel_number,
            'total_invoices'              => $bankInvoiceDetail->transaction->total_invoices,
            'net_value'                   => $netValue,
            'note'                        => $bankInvoiceDetail->transaction->note,
        ];
    }
}
