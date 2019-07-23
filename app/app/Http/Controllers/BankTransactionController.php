<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Models\BankAccount;
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
        if ($request->isMethod('post')) {
            $records = $this->bankTransactionService->getAll(null);
            $dt      = datatables()
                ->of($records)
                ->setTransformer(new BankTransactionTransformer);
            return $dt->make(true);
        }
        return view('/bank-transaction/index');
    }

    public function create(Request $request)
    {
        $action       = 'bank-transaction/create';
        $title        = 'Incluir transação';
        $model        = new BankInvoiceDetail();
        $cycles       = BankInvoiceTransaction::getTypeCicles();
        $bankAccounts = BankAccount::all();

        $categories        = $this->bankCategoryService->getAll(2);
        $categoryTransform = new BankCategoryTransformer();
        $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), 2));

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
        $action       = "bank-transaction/update/{$id}";
        $title        = 'Alterar lançamento';
        $model        = $this->bankTransactionService->find($id);
        $bankAccounts = BankAccount::all();

        $categories        = $this->bankCategoryService->getAll(2);
        $categoryTransform = new BankCategoryTransformer();
        $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), 2));

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $model    = $this->bankTransactionService->update($model, $postData, 'update');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o lançamento.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Lançamento(s) alterado(s) com sucesso.']);
            }
            return redirect("bank-account/");
        }
        $model = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
        return view('bank-transaction/_form', compact('action', 'title', 'model', 'bankAccounts', 'categoryOptions'));
    }

}
