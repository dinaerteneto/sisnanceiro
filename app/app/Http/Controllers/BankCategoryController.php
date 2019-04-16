<?php

namespace App\Http\Controllers;

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

    public function index()
    {
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
        return view('bank-category/index', compact('categoriesReceive', 'categoriesPay'));
    }

    public function create($mainParentCategoryId) 
    {
        $model = null;
        return view('bank-category/create', compact('model'));
    }

}
