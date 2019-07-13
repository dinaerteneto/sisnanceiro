<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\Sale;
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
        if ($request->isMethod('post')) {
            $data = $request->get('Sale');
            $data = array_replace($model->getAttributes(), $data);

            $model = $this->saleService->update($model, $data, 'update');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Falha na tentativa de alterar a venda.']);
            } else {
                $request->session()->flash('success', ['message' => 'Venda alterada com sucesso.']);
            }
            return redirect('sale');
        } else {
            $statues = Sale::getStatues();
            return view('sale/update', compact('model', 'statues'));
        }
    }

    public function cancel(Request $request, $id)
    {
        $model = $this->saleService->find($id);
        $data  = array_replace($model->getAttributes(), ['status' => Sale::STATUS_CANCELED]);
        $model = $this->saleService->update($model, $data, 'update');
        if (method_exists($model, 'getErrors') && $model->getErrors()) {
            return $this->apiSuccess(['success' => false]);
        } else {
            $request->session()->flash('success', ['message' => 'Venda cancelada com sucesso.']);
            return $this->apiSuccess(['success' => true]);
        }

    }

    public function ask($id)
    {
        $model = $this->saleService->find($id);
        $model = fractal($model, new SaleTransform());
        $sale  = $model->toArray()['data'];
        return View('sale/ask', compact('sale'));
    }

    public function coupon($id)
    {
        $model = $this->saleService->find($id);
        $model = fractal($model, new SaleTransform());
        $sale  = $model->toArray()['data'];
        return view('sale/coupon', compact('sale'));
    }

    function print($id) {
        $model = $this->saleService->find($id);
        $model = fractal($model, new SaleTransform());
        $sale  = $model->toArray()['data'];
        return view('sale/print', compact('sale'));
    }

    public function view($id)
    {
        $model = $this->saleService->find($id);
        $model = fractal($model, new SaleTransform());
        $sale  = $model->toArray()['data'];
        return view('sale/view', compact('sale'));
    }

    public function copy($id)
    {
        $model = $this->saleService->find($id);
        $model = fractal($model, new SaleTransform());
        $sale  = $model->toArray()['data'];
        return view('sale/copy', compact('sale'));
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
