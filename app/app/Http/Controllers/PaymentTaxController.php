<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Services\PaymentTaxService;
use Sisnanceiro\Transformers\PaymentTaxTransformer;

class PaymentTaxController extends Controller
{

    public function __construct(PaymentTaxService $paymentTaxService)
    {
        $this->paymentTaxService = $paymentTaxService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->paymentTaxService
                ->findBy('payment_method_id', $request->get('payment_method_id'));
            $records = $records ? $records->get() : [];
            $dt      = datatables()->of($records)->setTransformer(new PaymentTaxTransformer);
            return $dt->make(true);
        }
        return view('payment-tax/index');
    }

}
