<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Services\BankCategoryService;
use Sisnanceiro\Transformes\BankCategoryTransform;

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
        $categoriesReceive    = [];
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
        $model  = new BankCategory;
        $action = "/bank-category/create/{$main_parent_category_id}/{$parent_category_id}";
        $title  = 'Incluir categoria';

        if ($request->isMethod('post')) {
            $data = array_merge($request->get('BankCategory'), [
                'main_parent_category_id' => $main_parent_category_id,
                'parent_category_id'      => !empty($parent_category_id) ? $parent_category_id : $main_parent_category_id,
                'status'                  => BankCategory::STATUS_ACTIVE,
            ]);
            $model = $this->bankCategoryService->store($data, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de incluir a categoria.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Categoria incluída com sucesso.']);
            }
            return redirect('bank-category/all');
        } else {
            $categories = [];
            if ($categories = $this->bankCategoryService->findByParentCategory($main_parent_category_id)) {
                $categories = fractal($categories, new BankCategoryTransform)->toArray()['data'];
            }
            return view('bank-category/_form', compact('model', 'categories', 'main_parent_category_id', 'parent_category_id', 'action', 'title'));
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->bankCategoryService->find($id);

        $action = "/bank-category/update/{$id}";
        $title  = "Categoria {$model->name}";

        $main_parent_category_id = $model->main_parent_category_id;
        $parent_category_id      = $model->parent_category_id;

        if ($request->isMethod('post')) {
            $data = $request->get('BankCategory');
            $data = array_merge($request->get('BankCategory'), [
                'id' => $model->id,
                'main_parent_category_id' => $model->main_parent_category_id,
                'parent_category_id'      => !empty($parent_category_id) ? $parent_category_id : $model->main_parent_category_id,
                'status'                  => $model->status,
            ]);
            $model = $this->bankCategoryService->update($model, $data, 'update');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar a categoria.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Categoria alterada com sucesso.']);
            }
            return redirect('bank-category/all');
        } else {
            $categories = [];
            if ($categories = $this->bankCategoryService->findByParentCategory($model->main_parent_category_id)) {
                $categories = fractal($categories, new BankCategoryTransform)->toArray()['data'];
            }
            return view('bank-category/_form', compact('model', 'categories', 'main_parent_category_id', 'parent_category_id', 'action', 'title'));
        }
    }

    public function delete($id)
    {
        if($this->bankCategoryService->destroy($id)) {
            return $this->apiSuccess(['success' => true]);
        }
    }

}
