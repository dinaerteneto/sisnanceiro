<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\BankCategoryRepository;

class BankCategoryService extends Service
{
    protected $rules = [
        'create' => [
            // 'company_id'              => 'required|int',
            'main_parent_category_id' => 'required|int',
            'parent_category_id'      => 'required|int',
            'name'                    => 'required|max:255',
            'status'                  => 'required|int',
        ],
        'update' => [
            // 'company_id'              => 'required|int',
            'main_parent_category_id' => 'required|int',
            'parent_category_id'      => 'required|int',
            'name'                    => 'required|max:255',
            'status'                  => 'required|int',
        ],
    ];

    public function __construct(Validator $validator, BankCategoryRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

    /**
     * return all categories based on $mainParentCategoryId
     * @param int $mainParentCategoryId
     * @return array
     */
    public function all($mainParentCategoryId)
    {
        return $this->repository->getCategories($mainParentCategoryId);
    }

    /** 
     * return all categories based on parent_category_id
     * @param int $parent_category_id
     * @return Collection
     */
    public function findByParentCategory($parent_category_id)
    {
        return $this->repository->findAllBy('parent_category_id', $parent_category_id);
    }

}
