<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Services\StoreProductBrandService;

class StoreProductBrandController extends Controller
{
    private $service;

    public function __construct(StoreProductBrandService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $postData = $request->get('StoreProductBrand');
            $model    = $this->service->store($postData, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                return $this->errorJson([]);
            }
        }
        return Response::json($model->getAttributes());
    }

}
