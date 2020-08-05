<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Services\BankAccountService;
use Sisnanceiro\Services\PaymentTaxService;
use Sisnanceiro\Transformers\PaymentTaxTransformer;

class PaymentTaxController extends Controller
{

    public function __construct(PaymentTaxService $paymentTaxService, BankAccountService $bankAccountService)
    {
        $this->paymentTaxService  = $paymentTaxService;
        $this->bankAccountService = $bankAccountService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->paymentTaxService
                ->findBy('payment_method_id', $request->post('payment_method_id'));
            $records = $records ? $records->get() : [];
            $dt      = datatables()->of($records)->setTransformer(new PaymentTaxTransformer);
            return $dt->make(true);
        }

        return view('payment-tax/index');
    }

    public function create(Request $request, $payment_method_id)
    {
        $bankAccounts = $this->bankAccountService->all();
        if ($request->isMethod('post')) {
            $data  = $request->post();
            $model = $this->paymentTaxService->store($data, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de incluir a taxa.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Taxa incluída com sucesso.']);
            }
            return redirect("payment-tax");
        }

        return view('payment-tax/_form', compact('payment_method_id', 'bankAccounts'));
    }

}
