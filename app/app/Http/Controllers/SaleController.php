<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Services\CustomerService;
use Sisnanceiro\Services\SaleService;
use Sisnanceiro\Services\StoreProductService;
use Sisnanceiro\Transformers\SaleStoreProductTransform;
use Sisnanceiro\Transformers\SaleCustomerTransform;

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
        return view('/sale/index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            echo '<pre>';
            print_r($_POST);
            exit;
        }
        return view('sale/create');
    }

    public function searchItem(Request $request)
    {
        $search  = $request->get('search');
        $records = $this->storeProductService->getAll($search)->get();
        if ($records) {
            $recordTransform = fractal($records, new SaleStoreProductTransform());

            $return = ['items' => $recordTransform->toArray()['data'], 'total_count' => count($records)];
            return Response::json($return);
        }
    }

    public function searchCustomer(Request $request)
    {
        $search  = $request->get('search');
        $records = $this->customerService->getAll($search)->get();
        if ($records) {
            $recordTransform = fractal($records, new SaleCustomerTransform());

            $return = ['items' => $recordTransform->toArray()['data'], 'total_count' => count($records)];
            return Response::json($return);
        }
    }

}
