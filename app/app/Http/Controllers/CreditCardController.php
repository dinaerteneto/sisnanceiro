<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\CreditCard;
use Sisnanceiro\Models\CreditCardBrand;
use Sisnanceiro\Services\CreditCardService;
use Sisnanceiro\Transformers\CreditCardTransformer;

class CreditCardController extends Controller
{
    protected $creditCardService;

    public function __construct(CreditCardService $creditCardService)
    {
        $this->creditCardService = $creditCardService;
    }

    public function index(Request $request)
    {
        $records = $this->creditCardService->all();
        $data    = (object) fractal($records, new CreditCardTransformer)->toArray()['data'];
        return view('/credit-card/index', compact('data'));
    }

    public function create(Request $request)
    {
        $title            = 'Cartão de crédito';
        $action           = '/credit-card/create';
        $model            = new CreditCard();
        $bankAccounts     = BankAccount::all();
        $creditCardBrands = CreditCardBrand::all();
        $dueDate          = null;
        if ($request->isMethod('post')) {
            $postData                        = $request->all();
            $postData['CreditCard']['limit'] = FloatConversor::convert($postData['CreditCard']['limit']);
            $model                           = $this->creditCardService->store($postData['CreditCard'], 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar o cartão de crédito.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Inclusão de cartão de crédito realizada com sucesso.']);
            }
            return redirect('credit-card');
        }
        return view('credit-card/_form', compact('bankAccounts', 'model', 'action', 'title', 'creditCardBrands'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            if ($this->creditCardService->destroy($id)) {
                return $this->apiSuccess(['success' => true]);
            }
            return Response::json(['success' => false, 'message' => 'Erro na tentativa de excluir o cartão de crédito.']);
        }
    }
}
