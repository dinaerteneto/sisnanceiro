<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;

class BankTransactionTransformer extends TransformerAbstract
{
    public function transform($bankInvoiceDetail)
    {
        $dueDateCarbon     = Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->due_date);
        $paymentDateCarbon = !empty($bankInvoiceDetail->payment_date) ? Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->payment_date) : null;
        $netValue          = Mask::currency($bankInvoiceDetail->net_value < 0 ? $bankInvoiceDetail->net_value * -1 : $bankInvoiceDetail->net_value);

        $name = null;
        $dued = false;
        if ($bankInvoiceDetail->main_parent_category_id == BankCategory::CATEGORY_TO_PAY) {
            if (!empty($bankInvoiceDetail->supplier_firstname)) {
                $name = $bankInvoiceDetail->supplier_firstname;
            }
            if (empty($bankInvoiceDetail->payment_date) && $dueDateCarbon->isPast()) {
                $dued = true;
            }
        }
        if ($bankInvoiceDetail->main_parent_category_id == BankCategory::CATEGORY_TO_RECEIVE) {
            if (!empty($bankInvoiceDetail->customer_firstname)) {
                $name = $bankInvoiceDetail->customer_firstname;
            }
            if (empty($bankInvoiceDetail->receive_date) && $dueDateCarbon->isPast()) {
                $dued = true;
            }
        }

        $labelStatus = null;
        if ($bankInvoiceDetail->bank_category_id != BankCategory::CATEGORY_INITIAL_BALANCE) {
            $labelStatus = 'yellow';
            if ($dued) {
                $labelStatus = 'red';
            }
            if ($bankInvoiceDetail->status == BankInvoiceDetail::STATUS_PAID) {
                $labelStatus = 'green';
            }
        }

        $labelLegend = [
            'yellow' => 'Pendente',
            'red'    => 'Vencida',
            'green'  => $bankInvoiceDetail->main_parent_category_id == BankCategory::CATEGORY_TO_PAY ? 'Paga' : 'Recebida',
        ];

        return [
            'id'                          => $bankInvoiceDetail->id,
            'bank_invoice_transaction_id' => $bankInvoiceDetail->bank_invoice_transaction_id,
            'supplier_id'                 => $bankInvoiceDetail->supplier_id,
            'customer_id'                 => $bankInvoiceDetail->customer_id,
            'bank_account_id'             => $bankInvoiceDetail->bank_account_id,
            'bank_category_id'            => $bankInvoiceDetail->bank_category_id,
            'main_category_id'            => $bankInvoiceDetail->main_parent_category_id,
            'status'                      => $bankInvoiceDetail->status,
            'label_status'                => $labelStatus,
            'label_legend'                => isset($labelLegend[$labelStatus]) ? $labelLegend[$labelStatus] : null,
            'due_date'                    => $dueDateCarbon->format('d/m/Y'),
            'payment_date'                => !empty($paymentDateCarbon) ? $paymentDateCarbon->format('d/m/Y') : null,
            'description'                 => $bankInvoiceDetail->note,
            'category_name'               => $bankInvoiceDetail->bank_category_name,
            'account_name'                => $bankInvoiceDetail->bank_account_name,
            'parcel_number'               => $bankInvoiceDetail->parcel_number,
            'total_invoices'              => $bankInvoiceDetail->total_invoices,
            'net_value'                   => $netValue,
            'note'                        => $bankInvoiceDetail->note,
            'description'                 => $bankInvoiceDetail->description,
            'name'                        => $name,
        ];
    }
}
