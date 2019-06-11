<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Services\PersonService;

class PersonController extends Controller
{

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    public function addContact(Request $request)
    {
        $modelContact     = new \stdClass();
        $modelContact->id = $request->id;
        $typeContacts     = $this->personService->getTypeContacts();
        return view('person/_form_contact', compact('modelContact', 'typeContacts'));
    }

    public function addAddress(Request $request)
    {
        $modelAddress     = new \stdClass();
        $modelAddress->id = $request->id;
        $typeAddresses    = $this->personService->getTypeAddresses();
        return view('person/_form_address', compact('modelAddress', 'typeAddresses'));
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
