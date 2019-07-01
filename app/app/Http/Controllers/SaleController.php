<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Services\CustomerService;
use Sisnanceiro\Services\SaleService;
use Sisnanceiro\Services\StoreProductService;
use Sisnanceiro\Transformers\SaleCustomerTransform;
use Sisnanceiro\Transformers\SaleStoreProductTransform;
use Sisnanceiro\Transformers\SaleTransform;

class SaleController extends Controller
{

    public function __construct(SaleService $saleService, StoreProductService $storeProductService, CustomerService $customerService)
    {
        $this->saleService         = $saleService;
        $this->storeProductService = $storeProductService;
        $this->customerService     = $customerService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->saleService->getAll();
            $dt      = datatables()->of($records);
            return $dt->filterColumn('firstname', function ($query, $keyword) {
                $query->whereRaw("firstname LIKE ?", ["%{$keyword}%"]);
            })->make(true);
        }
        return view('/sale/index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $model    = $this->saleService->create($postData);
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a venda.', 'errors' => $model->getErrors()]);
                return redirect("sale/index");
            } else {
                return redirect("sale/ask/{$model->id}");
            }
        }
        return view('sale/create');
    }

    public function update(Request $request, $id)
    {
        $model = $this->saleService->find($id);

        return view('sale/update');
    }

    public function ask($id)
    {
        $model = $this->saleService->find($id);
        $model = fractal($model, new SaleTransform());
        $sale  = $model->toArray()['data'];
        return View('sale/ask', compact('sale'));
    }

    function print($id) {
        $model = $this->saleService->find($id);
        $model = fractal($model, new SaleTransform());
        $sale  = $model->toArray()['data'];
        return view('sale/print', compact('sale'));
    }

    public function searchItem(Request $request)
    {
        $search  = $request->get('term');
        $records = $this->storeProductService->getAll($search)->get();
        if ($records) {
            $recordTransform = fractal($records, new SaleStoreProductTransform());

            $return = ['items' => $recordTransform->toArray()['data'], 'total_count' => count($records)];
            return Response::json($return);
        }
    }

    public function searchCustomer(Request $request)
    {
        $search  = $request->get('term');
        $records = $this->customerService->getAll($search)->get();
        if ($records) {
            $recordTransform = fractal($records, new SaleCustomerTransform());

            $return = ['items' => $recordTransform->toArray()['data'], 'total_count' => count($records)];
            return Response::json($return);
        }
    }

    public function delete($id)
    {
        if ($this->saleService->destroy($id)) {
            return $this->apiSuccess(['success' => true, 'remove-tr' => true]);
        }
    }

}
