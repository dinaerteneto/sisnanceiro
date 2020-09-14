<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\CreditCard;
use Sisnanceiro\Services\BankCategoryService;
use Sisnanceiro\Services\BankTransactionService;
use Sisnanceiro\Services\CreditCardService;
use Sisnanceiro\Transformers\BankCategoryTransformer;
use Sisnanceiro\Transformers\BankTransactionCreditCardTransformer;
use Sisnanceiro\Transformers\BankTransactionTotalTransformer;

class CreditCardTransactionController extends Controller
{
 protected $bankTransactionService;

 public function __construct(
  BankTransactionService $bankTransactionService,
  BankCategoryService $bankCategoryService,
  CreditCardService $creditCardService
 ) {
  $this->bankTransactionService = $bankTransactionService;
  $this->bankCategoryService    = $bankCategoryService;
  $this->creditCardService      = $creditCardService;
 }

 public function index(Request $request, $id)
 {
  if ($request->isMethod('post')) {
   $records = $this->bankTransactionService->getAll($request->get('extra_search'));
   $dt      = datatables()
    ->of($records)
    ->setTransformer(new BankTransactionCreditCardTransformer);
   return $dt->make(true);
  }

  $creditCards = CreditCard::all();
  $model       = CreditCard::find($id);
  $title       = $model->name;

  $dateStartDate = isset($request->start_date) && !empty($request->start_date) ? $request->start_date : date('Y-m') . "-{$model->closing_day}";
  $dateDueDate   = isset($request->end_date) && !empty($request->end_date) ? $request->end_date : date('Y-m') . "-{$model->payment_day}";

  $startDate       = Carbon::createFromFormat('Y-m-d', $dateStartDate);
  $dueDate         = Carbon::createFromFormat('Y-m-d', $dateDueDate);
  $previousEndDate = clone $dueDate;
  $nextEndDate     = clone $dueDate;

  $currentDate = clone $startDate;
  $currentDate = $startDate->format('m/Y');

  $previousStartDate = clone $startDate;
  $previousStartDate = $previousStartDate->addMonth(-1)->format('Y-m-d');
  $previousEndDate   = $previousEndDate->addMonth(-1)->format('Y-m-d');

  $nextStartDate = clone $startDate;
  $nextStartDate = $nextStartDate->addMonth()->format('Y-m-d');
  $nextEndDate   = $nextEndDate->addMonth()->format('Y-m-d');

  $dueDate       = $dueDate->format('d/m/Y');
  $endDateFormat = clone $startDate;

  $endDate   = $startDate->addMonth()->format('Y-m-d');
  $startDate = $startDate->addMonth(-1)->format('Y-m-d');

  $status = 'Fechada';
  if ($endDateFormat->isFuture()) {
   $status = 'Aberta';
  }
  $endDateFormat = $endDateFormat->format('d/m/Y');
  $invoice       = $this->creditCardService->getInvoice($id, $dateDueDate);
  $isPaid        = false;
  if ($invoice) {
   $isPaid = BankInvoiceDetail::STATUS_PAID === $invoice->status;
  }

  return view('/credit-card/index-invoice', compact(
   'urlMain',
   'title',
   'creditCards',
   'model',
   'startDate',
   'endDate',
   'dueDate',
   'currentDate',
   'endDateFormat',
   'previousStartDate',
   'previousEndDate',
   'nextStartDate',
   'nextEndDate',
   'status',
   'invoice',
   'isPaid'
  )
  );
 }

 public function create(Request $request, $id)
 {
  if ($request->isMethod('post')) {

   $postData = $request->all();

   $postData['BankInvoiceDetail']['credit_card_id'] = $id;

   $model = $this->creditCardService->addCreditCardInvoice($postData, 'create');
   if (method_exists($model, 'getErrors') && $model->getErrors()) {
    $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a transação.', 'errors' => $model->getErrors()]);
   } else {
    $this->creditCardService->closeInvoice();
    $request->session()->flash('success', ['message' => 'Transação criada com sucesso.']);
   }
   $urlMain = "/credit-card/{$id}";
   return redirect($urlMain);
  }

  $title                  = 'Incluir despesa de cartão';
  $model                  = new BankInvoiceDetail();
  $model->competence_date = date('d/m/Y');
  $creditCards            = CreditCard::all();

  $mainCategoryId    = BankCategory::CATEGORY_TO_PAY;
  $categories        = $this->bankCategoryService->getAll($mainCategoryId);
  $categoryTransform = new BankCategoryTransformer();
  $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

  $action = "/credit-card/{$id}/create";
  return view('credit-card-transaction/_form', compact('model', 'creditCards', 'action', 'title', 'categoryOptions', 'id'));
 }

 public function update(Request $request, $credit_card_id, $id)
 {
  if ($request->isMethod('post')) {
   $postData = $request->all();
   $option   = BankTransactionService::OPTION_ALL;
   $model    = $this->bankTransactionService->findByInvoice($id);
   $model    = $this->creditCardService->updateInvoices($model, $postData, $option);
   if (method_exists($model, 'getErrors') && $model->getErrors()) {
    $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o lançamento.', 'errors' => $model->getErrors()]);
   } else {
    $request->session()->flash('success', ['message' => 'Lançamento(s) alterado(s) com sucesso.']);
   }
   return redirect("/credit-card/{$credit_card_id}");
  }

  $title          = 'Alterar despesa de cartão';
  $creditCards    = CreditCard::all();
  $mainCategoryId = BankCategory::CATEGORY_TO_PAY;

  $action = "/credit-card/{$credit_card_id}/update/{$id}";
  $model  = $this->bankTransactionService->findByInvoice($id);

  $categories        = $this->bankCategoryService->getAll($mainCategoryId);
  $categoryTransform = new BankCategoryTransformer();
  $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

  $model = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
  return view('credit-card-transaction/_form_update', compact('model', 'creditCards', 'action', 'title', 'categoryOptions'));
 }

 public function delete(Request $request, $credit_card_id, $id)
 {
  if ($request->isMethod('post')) {
   if ($this->creditCardService->destroyInvoices($id)) {
    return $this->apiSuccess(['success' => true]);
   }
   return Response::json(['success' => false, 'message' => 'Erro na tentativa de excluir o(s) lançamentos.']);
  }
 }

 public function getTotal(Request $request)
 {
  $model    = (object) $this->bankTransactionService->getTotal($request->get('extra_search'));
  $response = fractal($model, new BankTransactionTotalTransformer)->toArray()['data'];
  return Response::json($response);
 }

 public function dueInvoiceDates(Request $request, $id)
 {
  $competenceDate  = $request->get('date');
  $aCompetenceDate = explode('/', $competenceDate);
  $dates           = [];
  $model           = CreditCard::find($id);
  for ($i = -1; $i <= 4; $i++) {
   $future = false;
   $date   = Carbon::createFromDate($aCompetenceDate[2], $aCompetenceDate[1], $model->payment_day)->addMonth($i);
   if (1 == $i) {
    $future = true;
   }
   $dates[] = [
    'date'     => $date->format('d/m/Y'),
    'selected' => $future,
   ];

  }
  return Response::json(['dates' => $dates]);
 }

 public function reopen(Request $request, $credit_card_id, $id)
 {
  $return = ['success' => true];
  $model  = $this->creditCardService->setOpen($id);
  if (method_exists($model, 'getErrors') && $model->getErrors()) {
   $return = ['success' => false];
  }
  return Response::json($return);
 }

 public function close(Request $request, $credit_card_id, $id)
 {
  $return = ['success' => true];
  $model  = $this->bankTransactionService->setClose($id);
  if (method_exists($model, 'getErrors') && $model->getErrors()) {
   $return = ['success' => false];
  }
  return Response::json($return);
 }

}
