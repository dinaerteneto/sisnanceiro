<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        return view('customer/index');
    }

    public function create(Request $request)
    {
        $modelAddress     = new \stdClass();
        $modelContact     = new \stdClass();
        $modelAddress->id = 'N0';
        $modelContact->id = 'N0';
        return view('customer/create', compact('modelAddress', 'modelContact'));
    }

}
