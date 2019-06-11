<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Services\CustomerService;
use Sisnanceiro\Services\PersonService;

class CustomerController extends Controller
{

    private $customerService;

    public function __construct(CustomerService $customerService, PersonService $personService)
    {
        $this->customerService = $customerService;
        $this->personService   = $personService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->customerService->getAll();
            $dt      = datatables()->of($records);
            return $dt->filterColumn('firstname', function ($query, $keyword) {
                $query->whereRaw("firstname LIKE ?", ["%{$keyword}%"]);
            })->make(true);
        }
        return view('customer/index');
    }

    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $customerPost  = $request->get('Customer');
            $customerModel = $this->customerService->store($customerPost, 'create');

            if (null !== $request->get('PersonAddress')) {
                foreach ($request->get('PersonAddress') as $keyCustomerAddress => $postCustomerAddress) {
                    $this->personService->storeAddress($customerModel, $postCustomerAddress);
                }
            }
            if (null !== $request->get('PersonContact')) {
                foreach ($request->get('PersonContact') as $keyCustomerContact => $postCustomerContact) {
                    $this->personService->storeContact($customerModel, $postCustomerContact);
                }
            }

            if (method_exists($customerModel, 'getErrors') && $customerModel->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de incluir o cliente.', 'errors' => $customerModel->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Cliente incluído com sucesso.']);
            }

            return redirect('customer/');
        } else {
            $modelAddress     = new \stdClass();
            $modelContact     = new \stdClass();
            $modelAddress->id = 'N0';
            $modelContact->id = 'N0';
            $typeContacts     = $this->personService->getTypeContacts();
            $typeAddresses    = $this->personService->getTypeAddresses();
            return view('customer/create', compact('modelAddress', 'modelContact', 'typeContacts', 'typeAddresses'));
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->personService->find($id);
        if ($request->isMethod('post')) {

        } else {
            $typeContacts  = $this->personService->getTypeContacts();
            $typeAddresses = $this->personService->getTypeAddresses();
            $addresses     = $model->addresses()->get();
            $contacts      = $model->contacts()->get();
            return view('customer/update', compact('model', 'addresses', 'contacts', 'typeContacts', 'typeAddresses'));
        }
    }

}
