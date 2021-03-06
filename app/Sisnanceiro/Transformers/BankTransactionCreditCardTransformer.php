<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;

class BankTransactionCreditCardTransformer extends TransformerAbstract
{
 public function transform($bankInvoiceDetail)
 {
  $dueDateCarbon        = Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->due_date);
  $competenceDateCarbon = Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->competence_date);
  $paymentDateCarbon    = !empty($bankInvoiceDetail->payment_date) ? Carbon::createFromFormat('Y-m-d', $bankInvoiceDetail->payment_date) : null;
  $netValue             = Mask::currency($bankInvoiceDetail->net_value < 0 ? $bankInvoiceDetail->net_value * -1 : $bankInvoiceDetail->net_value);

  $name = null;
  $dued = false;
  if (BankCategory::CATEGORY_TO_PAY == $bankInvoiceDetail->main_parent_category_id) {
   if (!empty($bankInvoiceDetail->supplier_firstname)) {
    $name = $bankInvoiceDetail->supplier_firstname;
   }

   if (empty($bankInvoiceDetail->payment_date) && $dueDateCarbon->isPast()) {
    $dued = true;
   }
  }
  if (BankCategory::CATEGORY_TO_RECEIVE == $bankInvoiceDetail->main_parent_category_id) {
   if (!empty($bankInvoiceDetail->customer_firstname)) {
    $name = $bankInvoiceDetail->customer_firstname;
   }
   if (empty($bankInvoiceDetail->receive_date) && $dueDateCarbon->isPast()) {
    $dued = true;
   }
  }

  $labelStatus = null;
  if (BankCategory::CATEGORY_INITIAL_BALANCE != $bankInvoiceDetail->bank_category_id) {
   $labelStatus = 'yellow';
   if ($dued) {
    $labelStatus = 'red';
   }
   if (BankInvoiceDetail::STATUS_PAID == $bankInvoiceDetail->status) {
    $labelStatus = 'green';
   }
  }

  $labelLegend = [
   'yellow' => 'Pendente',
   'red'    => 'Vencida',
   'green'  => BankCategory::CATEGORY_TO_PAY == $bankInvoiceDetail->main_parent_category_id ? 'Paga' : 'Recebida',
  ];

  $transaction = isset($bankInvoiceDetail->transaction) ? $bankInvoiceDetail->transaction : $bankInvoiceDetail;

  return [
   'id'                          => $bankInvoiceDetail->id,
   'bank_invoice_transaction_id' => $bankInvoiceDetail->bank_invoice_transaction_id,
   'supplier_id'                 => $bankInvoiceDetail->supplier_id,
   'customer_id'                 => $bankInvoiceDetail->customer_id,
   'bank_account_id'             => $bankInvoiceDetail->bank_account_id,
   'credit_card_id'              => $bankInvoiceDetail->credit_card_id,
   'bank_category_id'            => $bankInvoiceDetail->bank_category_id,
   'main_category_id'            => $bankInvoiceDetail->main_parent_category_id,
   'is_credit_card_invoice'      => $bankInvoiceDetail->is_credit_card_invoice,
   'status'                      => $bankInvoiceDetail->status,
   'label_status'                => $labelStatus,
   'label_legend'                => isset($labelLegend[$labelStatus]) ? $labelLegend[$labelStatus] : null,
   'due_date'                    => $dueDateCarbon->format('d/m/Y'),
   'competence_date'             => $competenceDateCarbon->format('d/m/Y'),
   'payment_date'                => !empty($paymentDateCarbon) ? $paymentDateCarbon->format('d/m/Y') : null,
   'description'                 => $transaction->description,
   'credit_card_name'            => $bankInvoiceDetail->credit_card_name,
   'category_name'               => $bankInvoiceDetail->bank_category_name,
   'account_name'                => $bankInvoiceDetail->bank_account_name,
   'parcel_number'               => $bankInvoiceDetail->parcel_number,
   'total_invoices'              => $transaction->total_invoices,
   'net_value'                   => $netValue,
   'net_value_original'          => $bankInvoiceDetail->net_value,
   'note'                        => $transaction->note,
   'name'                        => $name,
  ];
 }
}
