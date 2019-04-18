<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Services\CompanyService;

class HomeController extends Controller
{
    protected $companyService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
        // $this->middleware('auth');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $model = $this->companyService->register($request->get('Register'));
            
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar seu cadastro.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Cadastro realizado com sucesso.']);
            }
            return redirect('/');
        }
    }
}
