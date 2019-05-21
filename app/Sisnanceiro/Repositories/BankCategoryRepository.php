<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankCategory;

class BankCategoryRepository extends Repository {

    public function __construct(BankCategory $model)
    {
        $this->model = $model;

    }

    /**
     * return all categories with active status
     * @param integer $mainParentCategoryId
     * @return Collection
     */
    public function getCategories($mainParentCategoryId)
    {

        $categories = BankCategory::select('id', 'main_parent_category_id', 'parent_category_id', 'name as text')
            ->where('status', '=', BankCategory::STATUS_ACTIVE)
            ->where('id', '>', 1)
            ->orWhere('company_id', 'is', null)
            ->orderBy('parent_category_id', 'id');
        if ($mainParentCategoryId) {
            $categories->where('main_parent_category_id', '=', $mainParentCategoryId);
        }
        if($categories) {
            return $categories->get();
        }
        return [];
    }    

}