<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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
        $model        = new BankInvoiceDetail();
        $bankAccounts = BankAccount::all();
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
        return view('bank-transaction-transfer/_form', compact('bankAccounts', 'model'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $option = $request->get('BankInvoiceTransaction')['option_delete'];
            if ($this->bankTransactionTransferService->destroy($id, $option)) {
                return $this->apiSuccess(['success' => true]);
            } else {
                return Response::json(['success' => false, 'message' => 'Erro na tentativa de excluir o(s) lançamentos.']);
            }
        } else {
            $model = $this->bankTransactionTransferService->findByInvoice($id);
            $model = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
            return view('bank-transaction-transfer/_form_delete', compact('model'));
        }
    }

}
