<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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
            $customerPost  = $request->all();
            $customerModel = $this->customerService->store($customerPost, 'create');
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
            $customerPost  = $request->all();
            $customerModel = $this->customerService->store($customerPost, 'update');
            if (method_exists($customerModel, 'getErrors') && $customerModel->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o cliente.', 'errors' => $customerModel->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Cliente alterado com sucesso.']);
            }
            return redirect('customer/');
        } else {
            if (!empty($model->birthdate)) {
                $carbonBirthdate  = Carbon::createFromFormat('Y-m-d', $model->birthdate);
                $model->birthdate = $carbonBirthdate->format('d/m/Y');
            }
            $typeContacts  = $this->personService->getTypeContacts();
            $typeAddresses = $this->personService->getTypeAddresses();
            $addresses     = $model->addresses()->get();
            $contacts      = $model->contacts()->get();
            return view('customer/update', compact('model', 'addresses', 'contacts', 'typeContacts', 'typeAddresses'));
        }
    }

    public function delete($id)
    {
        if ($this->personService->destroy($id)) {
            return $this->apiSuccess(['success' => true, 'remove-tr' => true]);
        }
    }

    public function createMin(Request $request) {
        if($request->isMethod('post')) {
            $customerPost  = $request->all();
            $customerModel = $this->customerService->store($customerPost, 'create');
            if (method_exists($customerModel, 'getErrors') && $customerModel->getErrors()) {
                return $this->errorJson([]);
            }
        }
        return Response::json($customerModel->getAttributes());
    }

}
