<?php

namespace App\Http\Controllers;

use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Services\BankCategoryService;
use Sisnanceiro\Transformes\BankCategoryTransform;
use Illuminate\Http\Request;

class BankCategoryController extends Controller
{
    protected $bankCategoryService;

    public function __construct(BankCategoryService $bankCategoryService)
    {
        $this->bankCategoryService = $bankCategoryService;

    }

    public function index(Request $request)
    {
        $mainParentCategoryId = $request->has('mainParentCategoryId') ? $request->mainParentCategoryId : BankCategory::CATEGORY_TO_RECEIVE;
        $categoriesReceive = [];
        if ($categoriesReceive = $this->bankCategoryService->all(BankCategory::CATEGORY_TO_RECEIVE)) {
            $categoryReceiveTransform = new BankCategoryTransform();
            $categoriesReceive        = $categoryReceiveTransform->buildTree($categoriesReceive->toArray(), BankCategory::CATEGORY_TO_RECEIVE);
        }
        $categoriesPay = [];
        if ($categoriesPay = $this->bankCategoryService->all(BankCategory::CATEGORY_TO_PAY)) {
            $categoryPayTransform = new BankCategoryTransform();
            $categoriesPay        = $categoryPayTransform->buildTree($categoriesPay->toArray(), BankCategory::CATEGORY_TO_PAY);
        }
        return view('bank-category/index', compact('categoriesReceive', 'categoriesPay', 'mainParentCategoryId'));
    }

    public function create(Request $request, $main_parent_category_id, $parent_category_id = null) 
    {
        $action = "/bank-category/create/{$main_parent_category_id}/{$parent_category_id}";
        $title = 'Incluir categoria';

        if($request->isMethod('post')) {
            $data = $request->get('BankCategory');
            if($this->bankCategoryService->store($data, 'create')) {
                $request->session()->flash('success', 'Categoria incluída com sucesso.');
            } else {
                $request->session()->flash('error', 'Erro na tentativa de incluir a categoria.');
            }
            return redirect('bank-category/all');
        } else {
            $categories = [];
            if($categories = $this->bankCategoryService->findByParentCategory($main_parent_category_id)) {
                $categories = fractal($categories, new BankCategoryTransform)->toArray()['data']; 
            }
            return view('bank-category/_form', compact('categories', 'main_parent_category_id', 'parent_category_id', 'action', 'title'));
        }
    }

}
