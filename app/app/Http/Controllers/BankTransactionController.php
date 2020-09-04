<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\BankInvoiceTransaction;
use Sisnanceiro\Services\BankCategoryService;
use Sisnanceiro\Services\BankTransactionService;
use Sisnanceiro\Services\CreditCardService;
use Sisnanceiro\Services\CustomerService;
use Sisnanceiro\Services\SupplierService;
use Sisnanceiro\Transformers\BankCategoryTransformer;
use Sisnanceiro\Transformers\BankTransactionTotalTransformer;
use Sisnanceiro\Transformers\BankTransactionTransformer;

class BankTransactionController extends Controller
{
 protected $bankTransactionService;

 public function __construct(
  BankTransactionService $bankTransactionService,
  BankCategoryService $bankCategoryService,
  CustomerService $customerService,
  SupplierService $supplierService,
  CreditCardService $creditCardService
 ) {
  $this->bankTransactionService = $bankTransactionService;
  $this->bankCategoryService    = $bankCategoryService;
  $this->customerService        = $customerService;
  $this->supplierService        = $supplierService;
  $this->creditCardService      = $creditCardService;
 }

 private function getMainCategory(Request $request)
 {
  $mainCategoryId = null;
  $urlMain        = null;
  $title          = 'Transações';

  if (isset($request->route()->getAction()['main_category_id'])) {
   $mainCategoryId = $request->route()->getAction()['main_category_id'];
   if (BankCategory::CATEGORY_TO_RECEIVE == $request->route()->getAction()['main_category_id']) {
    $urlMain = '/bank-transaction/receive';
    $title   = 'Contas a receber';
   } else {
    $urlMain = '/bank-transaction/pay';
    $title   = 'Contas a pagar';
   }
  }

  return [
   'main_category_id' => $mainCategoryId,
   'title'            => $title,
   'url'              => $urlMain,
  ];

 }

 public function index(Request $request)
 {
  if ($request->isMethod('post')) {
   $records = $this->bankTransactionService->getAll($request->get('extra_search'));
   $dt      = datatables()
    ->of($records)
    ->setTransformer(new BankTransactionTransformer);
   return $dt->make(true);
  }

  $mainCategory   = $this->getMainCategory($request);
  $title          = $mainCategory['title'];
  $urlMain        = $mainCategory['url'];
  $mainCategoryId = $mainCategory['main_category_id'];
  $bankAccounts   = BankAccount::all();

  return view('/bank-transaction/index', compact('urlMain', 'title', 'mainCategoryId', 'bankAccounts'));
 }

 public function create(Request $request)
 {

  if ($request->isMethod('post')) {
   $postData = $request->all();
   $model    = $this->bankTransactionService->store($postData, 'create');
   if (method_exists($model, 'getErrors') && $model->getErrors()) {
    $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a transação.', 'errors' => $model->getErrors()]);
   } else {
    $request->session()->flash('success', ['message' => 'Transação criada com sucesso.']);
   }
   return redirect($urlMain);
  }

  $mainCategory   = $this->getMainCategory($request);
  $title          = $mainCategory['title'];
  $urlMain        = $mainCategory['url'];
  $mainCategoryId = $mainCategory['main_category_id'];

  $action       = "{$urlMain}/create";
  $title        = BankCategory::CATEGORY_TO_PAY == $mainCategoryId ? 'Incluir conta a pagar' : 'Incluir contas a receber';
  $model        = new BankInvoiceDetail();
  $cycles       = BankInvoiceTransaction::getTypeCicles();
  $bankAccounts = BankAccount::all();

  $categories        = $this->bankCategoryService->getAll($mainCategoryId);
  $categoryTransform = new BankCategoryTransformer();
  $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

  $customers = $this->customerService->getAll()->get();
  $suppliers = $this->supplierService->getAll()->get();

  return view('bank-transaction/_form', compact('action', 'title', 'model', 'cycles', 'bankAccounts', 'categoryOptions', 'mainCategory', 'suppliers', 'customers'));
 }

 public function update(Request $request, $id)
 {
  if ($request->isMethod('post')) {
   $postData = $request->all();
   $option   = $request->get('BankInvoiceTransaction')['option_update'];

   $model = $this->bankTransactionService->updateInvoices($model, $postData, $option);
   if (method_exists($model, 'getErrors') && $model->getErrors()) {
    $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o lançamento.', 'errors' => $model->getErrors()]);
   } else {
    $request->session()->flash('success', ['message' => 'Lançamento(s) alterado(s) com sucesso.']);
   }
   return redirect($urlMain);
  }

  $mainCategory   = $this->getMainCategory($request);
  $title          = $mainCategory['title'];
  $urlMain        = $mainCategory['url'];
  $mainCategoryId = $mainCategory['main_category_id'];

  $action       = "{$urlMain}/update/{$id}";
  $title        = BankCategory::CATEGORY_TO_PAY == $mainCategoryId ? 'Alterar conta a pagar' : 'Alterar contas a receber';
  $model        = $this->bankTransactionService->findByInvoice($id);
  $bankAccounts = BankAccount::all();

  $categories        = $this->bankCategoryService->getAll($mainCategoryId);
  $categoryTransform = new BankCategoryTransformer();
  $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

  $customers = $this->customerService->getAll()->get();
  $suppliers = $this->supplierService->getAll()->get();

  $model = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
  return view('bank-transaction/_form_update', compact('action', 'title', 'model', 'bankAccounts', 'categoryOptions', 'mainCategory', 'suppliers', 'customers'));
 }

 public function delete(Request $request, $id)
 {
  if ($request->isMethod('post')) {
   $option = $request->get('BankInvoiceTransaction')['option_delete'];
   if ($this->bankTransactionService->destroyInvoices($id, $option)) {
    return $this->apiSuccess(['success' => true]);
   } else {
    return Response::json(['success' => false, 'message' => 'Erro na tentativa de excluir o(s) lançamentos.']);
   }
  } else {
   $model  = $this->bankTransactionService->findByInvoice($id);
   $model  = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
   $action = BankCategory::CATEGORY_TO_PAY == $model->main_category_id ? "/bank-transaction/pay/delete/{$model->id}" : "/bank-transaction/receive/delete/{$model->id}";
   return view('bank-transaction/_form_delete', compact('model', 'action'));
  }
 }

 public function setPaid($id)
 {
  $return = ['success' => false];
  $model  = $this->bankTransactionService->setPaid($id);
  if ($model) {
   $return = ['success' => true];
  }
  return Response::json($return);
 }

 public function getTotalByMainCategory(Request $request)
 {
  $model    = (object) $this->bankTransactionService->getTotal($request->get('extra_search'));
  $response = fractal($model, new BankTransactionTotalTransformer)->toArray()['data'];
  return Response::json($response);
 }

 public function partialPay(Request $request, $id)
 {
  if ($request->isMethod('post')) {
   $postData = $request->all();
   $model    = $this->creditCardService->partialPay($postData, $id);
   if (method_exists($model, 'getErrors') && $model->getErrors()) {
    $request->session()->flash('error', ['message' => 'Erro na tentativa de efetuar o pagamento parcial.', 'errors' => $model->getErrors()]);
   } else {
    $request->session()->flash('success', ['message' => 'Pagamento parcial efetuado com sucesso.']);
   }
   return redirect('/bank-transaction');
  }

  $model        = $this->bankTransactionService->findByInvoice($id);
  $creditCard   = $model->transaction->creditCard;
  $bankAccounts = BankAccount::all();
  $dueDate      = Carbon::createFromFormat('Y-m-d', $model->due_date)->format('d/m/Y');

  return view('bank-transaction/_form_partial_pay', compact('model', 'creditCard', 'bankAccounts', 'dueDate'));
 }

}
