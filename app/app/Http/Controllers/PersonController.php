<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PersonController extends Controller
{
    public function addContact(Request $request)
    {
        $modelContact     = new \stdClass();
        $modelContact->id = $request->id;
        return view('person/_form_contact', compact('modelContact'));
    }

    public function addAddress(Request $request)
    {
        $modelAddress     = new \stdClass();
        $modelAddress->id = $request->id;
        return view('person/_form_address', compact('modelAddress'));
    }

    public function delContact($id)
    {
        return Response::json(['success' => true]);
    }

    public function delAddress($id)
    {
        return Response::json(['success' => true]);
    }

}
