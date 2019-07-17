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

    public function __construct(BankTransactionService $transactionService, BankCategoryService $bankCategoryService)
    {
        $this->transactionService  = $transactionService;
        $this->bankCategoryService = $bankCategoryService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->transactionService->all();
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
            $postData = $request->get('BankInvoiceDetail');
            $model    = $this->bankTransaction->store($postData, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a transação.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Transação criada com sucesso.']);
            }
            return redirect("bank-transaction/");
        }
        return view('bank-transaction/_form', compact('action', 'title', 'model', 'cycles', 'bankAccounts', 'categoryOptions'));
    }

}
