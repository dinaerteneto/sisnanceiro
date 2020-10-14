<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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

 private function mapData(array $data = [])
 {
  $dataTransaction = $data['BankInvoiceTransaction'];
  $dataDetail      = $data['BankInvoiceDetail'];

  $totalInvoices = isset($dataTransaction['total_invoice']) && !empty($dataTransaction['total_invoice']) ? (int) $dataTransaction['total_invoice'] : 1;
  $netValue      = FloatConversor::convert($dataDetail['net_value']);
  $totalValue    = $netValue;

  $netValue = $netValue / $totalInvoices;

  $ret = [
   'BankInvoiceTransaction' => array_merge($dataTransaction, [
    'description'    => nl2br($dataTransaction['description']),
    'total_invoices' => $totalInvoices,
    'total_value'    => $totalValue,
    'type_cycle'     => isset($dataTransaction['type_cycle']) ? $dataTransaction['type_cycle'] : 0,
   ]),
   'BankInvoiceDetail'      => array_merge($dataDetail, [
    'net_value' => $netValue,
   ]),
  ];

  return $ret;

 }

 public function update(Model $model, array $data, $rules = 'update')
 {
  \DB::beginTransaction();
  try {
   $parent = parent::update($model, $data, $rules);
   $this->repository->updateAllDueDate($model);

   $dueDate = date('Y-m') . "-{$model->payment_day}";
   $invoice = $this->bankInvoiceDetailRepository->findCreditCardByDueDate($model->id, $dueDate);
   if ($invoice) {
    $this->__setTotalValues($invoice, $model->id);
   }
   \DB::commit();
   return $parent;
  } catch (\Exception $e) {
   dd($e);
   \DB::rollBack();
   throw new \Exception('Erro na tentativa de alterar os dados do cartão.');
   return false;
  }
 }

 public function closeInvoice()
 {

  $sql = "
    SELECT SUM(bid.net_value) AS net_value
    , bid.due_date
    , bid.credit_card_id
    , bid.bank_account_id
 FROM bank_invoice_detail bid
 LEFT JOIN bank_invoice_transaction bit2
   ON bid.due_date = bit2.credit_card_due_date
  AND bit2.credit_card_id = bid.credit_card_id
  AND bit2.deleted_at IS NULL

WHERE bid.credit_card_id IS NOT NULL
  AND bid.deleted_at IS NULL
  AND bit2.id IS NULL
  AND bid.due_date <= NOW()

GROUP BY bid.due_date
    , bid.credit_card_id
    ";

  $query = \DB::select($sql);

  if ($query) {

   foreach ($query as $invoice) {
    $date       = $invoice->due_date;
    $dateCarbon = Carbon::createFromFormat('Y-m-d', $date);
    $aData      = [
     'BankInvoiceTransaction' => [
      'credit_card_id'         => $invoice->credit_card_id,
      'note'                   => "Fatura do cartão de crédito",
      'description'            => "Fatura do cartão de crédito",
      'credit_card_due_date'   => $date,
      'is_credit_card_invoice' => true,
     ],
     'BankInvoiceDetail'      => [
      'bank_category_id' => BankCategory::CATEGORY_CREDIT_INVOICE,
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
   $totalInvoice = isset($input['BankInvoiceTransaction']['total_invoice']) ? $input['BankInvoiceTransaction']['total_invoice'] : 1;
   $netValue     = ($netValue / $totalInvoice);

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
      'bank_category_id' => BankCategory::CATEGORY_CREDIT_INVOICE,
      'bank_account_id'  => $creditCard->bank_account_id,
      'net_value'        => $netValue,
      'parcel_number'    => 1,
      'due_date'         => $inputDueDate,
     ],
    ];
    $invoice = $this->bankTransactionService->store($aData, 'create');
    $invoice = $invoice[0];
    if (BankInvoiceDetail::STATUS_PAID === $invoice->status) {
     $this->validator->addError('', 'due_date', 'Não é possível incluir lançamentos numa fatura que esta paga.');
    }
   }

   $mapData = $this->mapData($input);

   $this->bankTransactionService->store($mapData, $rules);
   $this->__setTotalValues($invoice, $creditCardId);
   \DB::commit();
   return true;
  } catch (\Exception $e) {

   \DB::rollBack();
   throw new \Exception('Erro na tentativa de incluir os lançamento.');
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

  if ($creditCardInvoice && BankInvoiceDetail::STATUS_PAID === $creditCardInvoice->status) {
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
  if ($creditCardInvoice) {
   $creditCardInvoice = $this->bankInvoiceDetailService->find($creditCardInvoice->id);
   $transaction       = $this->bankTransactionService->find($creditCardInvoice->bank_invoice_transaction_id);

   if ($transaction && $query) {
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
   }

   return true;
  }
  return false;
 }

}
