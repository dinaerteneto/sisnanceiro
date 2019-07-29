<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\BankInvoiceTransaction;
use Sisnanceiro\Services\BankCategoryService;
use Sisnanceiro\Services\BankTransactionService;
use Sisnanceiro\Transformers\BankCategoryTransformer;
use Sisnanceiro\Transformers\BankTransactionTransformer;

class BankTransactionController extends Controller
{
    protected $transactionService;

    public function __construct(BankTransactionService $bankTransactionService, BankCategoryService $bankCategoryService)
    {
        $this->bankTransactionService = $bankTransactionService;
        $this->bankCategoryService    = $bankCategoryService;
    }

    public function index(Request $request)
    {
        $urlMain = null;
        $title   = 'Transações';
        if (isset($request->route()->getAction()['main_category_id']) && $request->route()->getAction()['main_category_id'] == BankCategory::CATEGORY_TO_RECEIVE) {
            $urlMain = '/bank-transaction/receive';
            $title   = 'Contas a receber';
        } else if (isset($request->route()->getAction()['main_category_id']) && $request->route()->getAction()['main_category_id'] == BankCategory::CATEGORY_TO_PAY) {
            $urlMain = '/bank-transaction/pay';
            $title   = 'Contas a pagar';
        }

        if ($request->isMethod('post')) {
            $records = $this->bankTransactionService->getAll(null);
            $dt      = datatables()
                ->of($records)
                ->setTransformer(new BankTransactionTransformer);
            return $dt->make(true);
        }
        return view('/bank-transaction/index', compact('urlMain', 'title'));
    }

    public function create(Request $request)
    {
        $mainCategoryId = $request->route()->getAction()['main_category_id'];
        $action         = '/bank-transaction/create';
        $title          = $mainCategoryId == BankCategory::CATEGORY_TO_PAY ? 'Incluir conta a pagar' : 'Incluir contas a receber';
        $model          = new BankInvoiceDetail();
        $cycles         = BankInvoiceTransaction::getTypeCicles();
        $bankAccounts   = BankAccount::all();

        $categories        = $this->bankCategoryService->getAll($mainCategoryId);
        $categoryTransform = new BankCategoryTransformer();
        $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $model    = $this->bankTransactionService->store($postData, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a transação.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Transação criada com sucesso.']);
            }
            return redirect("bank-transaction/");
        }
        return view('bank-transaction/_form', compact('action', 'title', 'model', 'cycles', 'bankAccounts', 'categoryOptions'));
    }

    public function update(Request $request, $id)
    {
        $mainCategoryId = $request->route()->getAction()['main_category_id'];
        $action         = "/bank-transaction/update/{$id}";
        $title          = $mainCategoryId == BankCategory::CATEGORY_TO_PAY ? 'Alterar conta a pagar' : 'Alterar contas a receber';
        $model          = $this->bankTransactionService->findByInvoice($id);
        $bankAccounts   = BankAccount::all();

        $categories        = $this->bankCategoryService->getAll($mainCategoryId);
        $categoryTransform = new BankCategoryTransformer();
        $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $option   = $request->get('BankInvoiceTransaction')['option_update'];

            $model = $this->bankTransactionService->updateInvoices($model, $postData, $option);
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o lançamento.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Lançamento(s) alterado(s) com sucesso.']);
            }
            return redirect("bank-transaction/");
        }

        $model = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
        return view('bank-transaction/_form_update', compact('action', 'title', 'model', 'bankAccounts', 'categoryOptions'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $option = $request->get('BankInvoiceTransaction')['option_delete'];
            if ($this->bankTransactionService->destroyInvoices($id, $option)) {
                $request->session()->flash('success', ['message' => 'Lançamento(s) excluído(s) com sucesso.']);
            } else {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de excluir o lançamento.']);
            }
            return redirect("bank-transaction/");
        } else {
            $model = $this->bankTransactionService->findByInvoice($id);
            $model = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
            return view('bank-transaction/_form_delete', compact('model'));
        }
    }

    public function setPaid($id)
    {
        $return = ['success' => false];
        $model  = $this->bankTransactionService->findByInvoice($id);
        $data   = ['status' => BankInvoiceDetail::STATUS_PAID];

        $model = $this->bankTransactionService->findByInvoice($id);
        if ($model->update($data)) {
            $return = ['success' => true];
        }
        return Response::json($return);
    }

}
