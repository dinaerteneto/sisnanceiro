<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Models\Bank;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Services\BankAccountService;

class BankAccountController extends Controller
{
    protected $bankAccountService;

    public function __construct(BankAccountService $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->bankAccountService->all();
            $dt      = datatables()->of($records);
            return $dt->make(true);
        }
        return view('/bank-account/index');
    }

    public function create(Request $request)
    {
        $action = 'bank-account/create';
        $title  = 'Incluir conta';
        $model  = new BankAccount();
        $banks  = Bank::all();

        if ($request->isMethod('post')) {
            $postData = $request->get('BankAccount');
            $model    = $this->bankAccountService->store($postData, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a conta.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Conta criada com sucesso.']);
            }
            return redirect("bank-account/");
        }
        return view('bank-account/_form', compact('action', 'title', 'model', 'banks'));
    }

    public function update(Request $request, $id)
    {
        $action = "bank-account/update/{$id}";
        $title  = 'Alterar conta';
        $model  = $this->bankAccountService->find($id);
        $banks  = Bank::all();

        if ($request->isMethod('post')) {
            $postData = $request->get('BankAccount');
            $model    = $this->bankAccountService->update($model, $postData, 'update');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar a conta.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Conta criada com sucesso.']);
            }
            return redirect("bank-account/");
        }

        return view('bank-account/_form', compact('action', 'title', 'model', 'banks'));
    }

    public function delete($id)
    {
        if ($this->bankAccountService->destroy($id)) {
            return $this->apiSuccess(['success' => true, 'remove-tr' => true]);
        }
        return $this->apiSuccess(['success' => false]);
    }

}
