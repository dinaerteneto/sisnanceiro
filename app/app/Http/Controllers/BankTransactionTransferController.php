<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Services\BankTransactionTransferService;
use Sisnanceiro\Transformers\BankTransactionTransferTransformer;

class BankTransactionTransferController extends Controller
{
    protected $bankTransactionTransferService;

    public function __construct(
        BankTransactionTransferService $bankTransactionService
    ) {
        $this->bankTransactionTransferService = $bankTransactionService;
    }

    public function index(Request $request)
    {
        $bankAccounts = BankAccount::all();
        if ($request->isMethod('post')) {
            $records = $this->bankTransactionTransferService->getAll($request->get('extra_search'));
            $dt      = datatables()
                ->of($records)
                ->setTransformer(new BankTransactionTransferTransformer);
            return $dt->make(true);
        }
        return view('/bank-transaction-transfer/index', compact('bankAccounts'));
    }

    public function create(Request $request)
    {
        $action       = '/bank-transaction/transfer/create';
        $model        = new BankInvoiceDetail();
        $bankAccounts = BankAccount::all();
        $dueDate      = null;
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $model    = $this->bankTransactionTransferService->store($postData, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a transferência.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Transferência realizada com sucesso.']);
            }
            return redirect('bank-transaction/transfer');
        }
        return view('bank-transaction-transfer/_form', compact('bankAccounts', 'model', 'action', 'dueDate'));
    }

    public function update(Request $request, $id)
    {
        $action       = "/bank-transaction/transfer/update/{$id}";
        $model        = $this->bankTransactionTransferService->find($id);
        $bankAccounts = BankAccount::all();
        $firstInvoice = $model->invoices()->get()->first();
        $dueDate      = Carbon::createFromFormat('Y-m-d', $firstInvoice->due_date)->format('d/m/Y');
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $model    = $this->bankTransactionTransferService->update($model, $postData);
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar a transferência.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Transferência alterada com sucesso.']);
            }
            return redirect('bank-transaction/transfer');
        }
        return view('bank-transaction-transfer/_form', compact('bankAccounts', 'model', 'action', 'dueDate'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            if ($this->bankTransactionTransferService->destroy($id)) {
                return $this->apiSuccess(['success' => true, 'remove-tr' => true]);
            }
            return $this->errorJson(['success' => false, 'message' => 'Erro na tentativa de excluir a transferência.']);
        }
    }

}
