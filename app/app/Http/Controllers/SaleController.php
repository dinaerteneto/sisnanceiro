<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Services\SaleService;
use Sisnanceiro\Services\StoreProductService;
use Sisnanceiro\Transformers\SaleStoreProductTransform;

class SaleController extends Controller
{

    public function __construct(SaleService $saleService, StoreProductService $storeProductService)
    {
        $this->saleService         = $saleService;
        $this->storeProductService = $storeProductService;
    }

    public function index(Request $request)
    {
        return view('/sale/index');
    }

    public function create(Request $request)
    {
        return view('sale/create');
    }

    public function searchItem(Request $request)
    {
        $search  = $request->get('search');
        $records = $this->storeProductService->getAll($search)->get();
        if ($records) {
            $recordTransform = fractal($records, new SaleStoreProductTransform());

            $return          = ['items' => $recordTransform->toArray()['data'], 'total_count' => count($records)];

           
            return Response::json($return);
        }
    }

}
