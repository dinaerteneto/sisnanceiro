<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\CreditCard;
use Sisnanceiro\Repositories\BankInvoiceDetailRepository;
use Sisnanceiro\Repositories\CreditCardRepository;
use Sisnanceiro\Services\BankInvoiceDetailService;
use Sisnanceiro\Services\BankTransactionService;

class CreditCardService extends Service
{

 protected $rules = [
  'create' => [
   'bank_account_id'      => 'required|int',
   'credit_card_brand_id' => 'required|int',
   'name'                 => 'required|string',
   'limit'                => 'required|numeric',
   'payment_day'          => 'required|int',
   'closing_day'          => 'required|int',
  ],
  'update' => [
   'bank_account_id'      => 'required|int',
   'credit_card_brand_id' => 'required|int',
   'name'                 => 'required|string',
   'limit'                => 'required|numeric',
   'payment_day'          => 'required|int',
   'closing_day'          => 'required|int',
  ],
 ];

 protected $repository;

 public function __construct(
  Validator $validator,
  CreditCardRepository $creditCardRepository,
  BankInvoiceDetailRepository $bankInvoiceDetailRepository,
  BankInvoiceDetailService $bankInvoiceDetailService,
  BankTransactionService $bankTransactionService
 ) {
  $this->validator                   = $validator;
  $this->repository                  = $creditCardRepository;
  $this->bankInvoiceDetailRepository = $bankInvoiceDetailRepository;
  $this->bankTransactionService      = $bankTransactionService;
  $this->bankInvoiceDetailService    = $bankInvoiceDetailService;
 }

 public function closeInvoice($date)
 {
  $query = \DB::table('bank_invoice_detail')
   ->selectRaw('
                SUM(bank_invoice_detail.net_value) AS net_value
              , bank_invoice_detail.credit_card_id
              , bank_invoice_detail.bank_account_id
              , credit_card.name'
   )
   ->join('credit_card', 'credit_card.id', '=', 'bank_invoice_detail.credit_card_id')
   ->leftJoin('bank_invoice_transaction', function ($join) use ($date) {
    $join->on('bank_invoice_transaction.credit_card_id', '=', 'bank_invoice_detail.credit_card_id')
     ->where('bank_invoice_transaction.credit_card_due_date', '=', $date);
   })
   ->where('due_date', $date)
   ->whereNotNull('bank_invoice_detail.credit_card_id')
   ->whereNull('bank_invoice_transaction.id')
   ->whereNull('bank_invoice_detail.deleted_at')
   ->whereNull('credit_card.deleted_at')
   ->groupBy('bank_invoice_detail.credit_card_id')
   ->get();

  if ($query) {
   $dateCarbon = Carbon::createFromFormat('Y-m-d', $date);
   foreach ($query as $invoice) {
    $aData = [
     'BankInvoiceTransaction' => [
      'credit_card_id'         => $invoice->credit_card_id,
      'note'                   => "Fatura do cartão de crédito",
      'description'            => "Fatura do cartão de crédito",
      'credit_card_due_date'   => $date,
      'id_credit_card_invoice' => true,
     ],
     'BankInvoiceDetail'      => [
      'bank_category_id' => BankCategory::CATEGORY_CREDIT_CARD_BALANCE,
      'bank_account_id'  => $invoice->bank_account_id,
      'net_value'        => $invoice->net_value,
      'parcel_number'    => 1,
      'due_date'         => $dateCarbon->format('d/m/Y'),
     ],
    ];
    $transaction = $this->bankTransactionService->store($aData, 'create');
    if (method_exists($transaction, 'getErrors') && $transaction->getErrors()) {
     throw new \Exception("Erro na tentativa de incluir a fatura.", 500);
    }
   }
  }
  return true;
 }

 public function partialPay($data = [], $id)
 {
  $model        = $this->bankTransactionService->findByInvoice($id);
  $paymentValue = FloatConversor::convert($data['BankInvoiceDetail']['net_value']);
  $balance      = ($model->net_value + $paymentValue);
  $dataUpd      = [
   'payment_value'    => $paymentValue,
   'net_value'        => $balance,
   'bank_category_id' => BankCategory::CATEGORY_CREDIT_CARD_BALANCE,
   'bank_account_id'  => $data['BankInvoiceDetail']['bank_account_id'],
  ];
  $modelUpd = $this->bankTransactionService->update($model, $dataUpd);
  if (method_exists($modelUpd, 'getErrors') && $modelUpd->getErrors()) {
   throw new \Exception("Erro ao alterar o valor da fatura atual.");
  }
  $this->bankTransactionService->setPaid($model->id);

  $carbonDueDate = Carbon::createFromFormat('Y-m-d', $model->due_date)->addMonth();
  $dueDateFormat = $carbonDueDate->format('d/m/Y');
  $newData       = [
   'BankInvoiceTransaction' => $model->transaction->getAttributes(),
   'BankInvoiceDetail'      => $model->getAttributes(),
  ];
  $newData['BankInvoiceTransaction']['id']                     = null;
  $newData['BankInvoiceTransaction']['credit_card_id']         = null;
  $newData['BankInvoiceTransaction']['is_credit_card_invoice'] = false;
  $newData['BankInvoiceTransaction']['description']            = 'Saldo da fatura anterior';
  $newData['BankInvoiceTransaction']['note']                   = 'Saldo da fatura anterior';
  $newData['BankInvoiceTransaction']['credit_card_due_date']   = $carbonDueDate->format('Y-m-d');

  $newData['BankInvoiceDetail']['id']               = null;
  $newData['BankInvoiceDetail']['credit_card_id']   = $model->transaction->credit_card_id;
  $newData['BankInvoiceDetail']['due_date']         = $dueDateFormat;
  $newData['BankInvoiceDetail']['total_invoices']   = 1;
  $newData['BankInvoiceDetail']['total_value']      = $balance;
  $newData['BankInvoiceDetail']['type_cycle']       = 0;
  $newData['BankInvoiceDetail']['bank_category_id'] = BankCategory::CATEGORY_CREDIT_CARD_BALANCE;
  $newData['BankInvoiceDetail']['payment_date']     = null;
  $newData['BankInvoiceDetail']['competence_date']  = date('d/m/Y');

  $newInvoice = $this->bankTransactionService->store($newData, 'create');
  if (method_exists($newInvoice, 'getErrors') && $newInvoice->getErrors()) {
   throw new \Exception("Erro ao incluir lançamento na próxima fatura.");
  }
  return $newInvoice;
 }

 public function isPaid($creditCardId, $dueDate)
 {
  $transaction = $this->bankInvoiceDetailRepository->findCreditCardByDueDate($creditCardId, $dueDate);
  if ($transaction) {
   return BankInvoiceDetail::STATUS_PAID === $transaction->status;
  }
  return false;
 }

 public function getInvoice($creditCardId, $dueDate)
 {
  return $this->bankInvoiceDetailRepository->findCreditCardByDueDate($creditCardId, $dueDate);
 }

 public function setOpen($id)
 {
  \DB::beginTransaction();
  try {
   $invoice     = $this->bankInvoiceDetailService->setOpen($id);
   $transaction = $this->bankTransactionService->find($invoice->bank_invoice_transaction_id);
   $dueDate     = Carbon::createFromFormat('Y-m-d', $invoice->due_date)->addMonth();

   //remove balance the next invoice if exists
   $invoiceBalance = $this->bankInvoiceDetailService
    ->repository
    ->where('due_date', '=', $dueDate->format('Y-m-d'))
    ->where('bank_category_id', '=', BankCategory::CATEGORY_CREDIT_CARD_BALANCE)
    ->where('credit_card_id', '=', $transaction->credit_card_id)
    ->first();
   if ($invoiceBalance) {
    $this->bankInvoiceDetailService->destroy($invoiceBalance->id);
   }
   \DB::commit();
   return true;
  } catch (\Exception $e) {
   \DB::rollBack();
  }
  return false;
 }

 public function addCreditCardInvoice(array $input, $rules)
 {
  \DB::beginTransaction();

  try {
   $creditCardId = $input['BankInvoiceDetail']['credit_card_id'];
   $creditCard   = CreditCard::find($creditCardId);
   $inputDueDate = $input['BankInvoiceDetail']['due_date'];
   $dateCarbon   = Carbon::createFromFormat('d/m/Y', $inputDueDate);
   $dueDate      = $dateCarbon->format('Y-m-d');
   $netValue     = FloatConversor::convert($input['BankInvoiceDetail']['net_value']);

   $input['BankInvoiceDetail']['bank_account_id'] = $creditCard->bank_account_id;

   //verify if is first transaction to invoice credit card by due date
   $invoice = $this->bankInvoiceDetailService->findCreditCardByDueDate($creditCardId, $dueDate);
   if (!$invoice) {
    // create the transaction and credit card invoice
    $aData = [
     'BankInvoiceTransaction' => [
      'credit_card_id'         => $creditCardId,
      'note'                   => "Fatura do cartão de crédito",
      'description'            => "Fatura do cartão de crédito",
      'credit_card_due_date'   => $dueDate,
      'is_credit_card_invoice' => true,
     ],
     'BankInvoiceDetail'      => [
      'bank_category_id' => BankCategory::CATEGORY_CREDIT_CARD_BALANCE,
      'bank_account_id'  => $creditCard->bank_account_id,
      'net_value'        => $netValue,
      'parcel_number'    => 1,
      'due_date'         => $inputDueDate,
     ],
    ];
    $invoice = $this->bankTransactionService->store($aData, 'create');
    if (method_exists($invoice, 'getErrors') && $invoice->getErrors()) {
     throw new \Exception("Erro na tentativa de criar a fatura do cartão.", 500);
    }
    if (BankInvoiceDetail::STATUS_PAID === $invoice->status) {
     $this->validator->addError('', 'due_date', 'Não é possível incluir lançamentos numa fatura que esta paga.');
    }
   }
   $this->bankTransactionService->store($input, $rules);
   $this->__setTotalValues($invoice, $creditCardId);
   \DB::commit();
   return true;
  } catch (\Exception $e) {
   \DB::rollBack();
   return false;
  }
 }

 public function updateInvoices($model, $postData, $option)
 {
  $invoice = $this->bankTransactionService->updateInvoices($model, $postData, $option);
  $this->__setTotalValues($model, $model->credit_card_id);
  return $invoice;
 }

 public function destroyInvoices($id)
 {
  $option            = BankTransactionService::OPTION_ALL;
  $invoice           = $this->bankInvoiceDetailService->find($id);
  $creditCardInvoice = $this->bankInvoiceDetailService->findCreditCardByDueDate($invoice->credit_card_id, $invoice->due_date);
  if (BankInvoiceDetail::STATUS_PAID === $creditCardInvoice->status) {
   $this->validator->addError('', 'due_date', 'Não é possível excluir lançamentos numa fatura que esta paga.');
   return false;
  }
  if ($this->bankTransactionService->destroyInvoices($id, $option)) {
   $this->__setTotalValues($invoice, $invoice->credit_card_id);
   return true;
  }
  return false;
 }

 private function __setTotalValues($invoice, $creditCardId)
 {
  $dueDate   = $invoice->due_date;
  $companyId = \Auth::user()->company_id;

  $query = \DB::query()
   ->selectRaw("SUM(bank_invoice_detail.net_value) AS net_value")
   ->from('bank_invoice_detail')
   ->where('bank_invoice_detail.company_id', $companyId)
   ->where('bank_invoice_detail.credit_card_id', $creditCardId)
   ->where('bank_invoice_detail.due_date', $dueDate)
   ->whereNull('bank_invoice_detail.deleted_at')
   ->first();

  $creditCardInvoice = $this->bankInvoiceDetailService->findCreditCardByDueDate($creditCardId, $dueDate);
  $creditCardInvoice = $this->bankInvoiceDetailService->find($creditCardInvoice->id);
  $transaction       = $this->bankTransactionService->find($creditCardInvoice->bank_invoice_transaction_id);

  if ($query) {
   $netValue = $query->net_value;

   $dataTransaction = array_merge($transaction->getAttributes(), ['total_value' => $netValue]);
   $dataInvoice     = array_merge($creditCardInvoice->getAttributes(),
    [
     'net_value'   => $netValue,
     'gross_value' => $netValue,
    ]
   );

   $transaction    = $this->bankTransactionService->update($transaction, $dataTransaction, 'update');
   $invoiceUpdated = $this->bankInvoiceDetailService->update($creditCardInvoice, $dataInvoice, 'update');
   if (method_exists($transaction, 'getErrors') && $transaction->getErrors()) {
    throw new \Exception("Erro na tentativa de atualizar o valor da fatura do cartão.", 500);
   }
   if (method_exists($invoiceUpdated, 'getErrors') && $invoiceUpdated->getErrors()) {
    throw new \Exception("Erro na tentativa de atualizar o valor da fatura do cartão.", 500);
   }

   return true;
  }
  return false;
 }

}
