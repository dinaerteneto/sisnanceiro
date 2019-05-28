<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Models\StoreProduct;

class StoreProductController extends Controller
{

    public function create(Request $request)
    {
        $model = new StoreProduct;
        if($request->isMethod('post')) {

        }
        return view('store/product/create', compact('model'));
    }

}
