<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Services\SupplierService;
use Sisnanceiro\Services\PersonService;

class SupplierController extends Controller
{

    private $SupplierService;

    public function __construct(SupplierService $SupplierService, PersonService $personService)
    {
        $this->SupplierService = $SupplierService;
        $this->personService   = $personService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->SupplierService->getAll();
            $dt      = datatables()->of($records);
            return $dt->filterColumn('firstname', function ($query, $keyword) {
                $query->whereRaw("firstname LIKE ?", ["%{$keyword}%"]);
            })->make(true);
        }
        return view('supplier/index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $SupplierPost  = $request->all();
            $SupplierModel = $this->SupplierService->store($SupplierPost, 'create');
            if (method_exists($SupplierModel, 'getErrors') && $SupplierModel->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de incluir o Fornecedor.', 'errors' => $SupplierModel->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Fornecedor incluído com sucesso.']);
            }
            return redirect('supplier/');
        } else {
            $modelAddress     = new \stdClass();
            $modelContact     = new \stdClass();
            $modelAddress->id = 'N0';
            $modelContact->id = 'N0';
            $typeContacts     = $this->personService->getTypeContacts();
            $typeAddresses    = $this->personService->getTypeAddresses();
            return view('supplier/create', compact('modelAddress', 'modelContact', 'typeContacts', 'typeAddresses'));
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->personService->find($id);
        if ($request->isMethod('post')) {
            $SupplierPost  = $request->all();
            $SupplierModel = $this->SupplierService->store($SupplierPost, 'update');
            if (method_exists($SupplierModel, 'getErrors') && $SupplierModel->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o Fornecedor.', 'errors' => $SupplierModel->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Fornecedor alterado com sucesso.']);
            }
            return redirect('supplier/');
        } else {
            if (!empty($model->birthdate)) {
                $carbonBirthdate  = Carbon::createFromFormat('Y-m-d', $model->birthdate);
                $model->birthdate = $carbonBirthdate->format('d/m/Y');
            }
            $typeContacts  = $this->personService->getTypeContacts();
            $typeAddresses = $this->personService->getTypeAddresses();
            $addresses     = $model->addresses()->get();
            $contacts      = $model->contacts()->get();
            return view('supplier/update', compact('model', 'addresses', 'contacts', 'typeContacts', 'typeAddresses'));
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
            $supplierPost  = $request->all();
            $supplierModel = $this->SupplierService->store($supplierPost, 'create');
            if (method_exists($supplierModel, 'getErrors') && $supplierModel->getErrors()) {
                return $this->errorJson([]);
            }
        }
        return Response::json($supplierModel->getAttributes());
    }
}
