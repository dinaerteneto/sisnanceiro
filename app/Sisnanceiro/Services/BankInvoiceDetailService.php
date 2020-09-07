<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Repositories\BankInvoiceDetailRepository;

class BankInvoiceDetailService extends Service
{

 protected $rules = [
  'create' => [
   'bank_category_id'            => 'required',
   'bank_invoice_transaction_id' => 'required',
   'gross_value'                 => 'required|numeric',
   'net_value'                   => 'required|numeric',
   'parcel_number'               => 'required',
   'due_date'                    => 'required',
   // 'status'                      => 'required',
  ],
  'update' => [
   'bank_category_id'            => 'required',
   // 'bank_account_id'             => 'required',
   'bank_invoice_transaction_id' => 'required',
   // 'gross_value'                 => 'required|numeric',
   // 'net_value'                   => 'required|numeric',
   // 'parcel_number'               => 'required',
   // 'due_date'                    => 'required',
   // 'status'                      => 'required',
  ],
 ];

 public function __construct(Validator $validator, BankInvoiceDetailRepository $repository)
 {
  $this->validator  = $validator;
  $this->repository = $repository;
 }

 /**
  * recalcule receive date based payment method
  * @param float $dueDate
  * @return float
  */
 private function __getReceiveDate($dueDate)
 {
  return $dueDate;
 }

 public function store(array $data, $rules = false)
 {
  if ('create' == $rules) {
   $data['receive_date'] = $this->__getReceiveDate($data['due_date']);
  }

  if ($this->creditCardInvoiceIsPaid($data)) {
   $this->validator->addError('', 'due_date', 'Não é possível incluir lançamentos numa fatura que esta paga.');
  }
  return parent::store($data, $rules);
 }

 /**
  * return BankInvoice of the inicial balance
  * @param BankAccount $bankAccount
  * @return BankInvoiceDetail
  */
 public function findInitialBalance(BankAccount $bankAccount)
 {
  return $this->repository->findInitialBalance($bankAccount);
 }

 public function setPaid($id)
 {
  $model = $this->repository->find($id);
  $data  = [
   'status'        => BankInvoiceDetail::STATUS_PAID,
   'payment_date'  => $this->__getReceiveDate($model->due_date),
   'payment_value' => !empty($model->payment_value) ? $model->payment_value : $model->net_value,
  ];
  return $model->update($data);
 }

 public function setOpen($id)
 {
  $model = $this->repository->find($id);
  $data  = [
   'status'        => BankInvoiceDetail::STATUS_ACTIVE,
   'payment_date'  => null,
   'payment_value' => null,
   'net_value'     => $model->gross_value,
  ];
  $model->update($data);
  return $model;
 }

 /**
  * verify if credit invoice is paid
  * return true if yes
  * @param array $data
  * @return boolean
  */
 public function creditCardInvoiceIsPaid($data)
 {
  if (isset($data['credit_card_id']) && !empty($data['credit_card_id'])) {
   $invoice = $this->repository->findCreditCardByDueDate($data['credit_card_id'], $data['due_date']);
   if ($invoice) {
    return BankInvoiceDetail::STATUS_PAID === $invoice->status;
   }
  }
  return false;
 }

 /**
  * get invoice the credit card based due date
  * @param int $creditCardId id of the credit card
  * @param string $dueDate due date of the credit card invoice (YYYY-MM-DD)
  * @return \Sisnanceiro\Models\BankInvoiceDetail
  */
 public function findCreditCardByDueDate($creditCardId, $dueDate)
 {
  return $this->repository->findCreditCardByDueDate($creditCardId, $dueDate);
 }

}
