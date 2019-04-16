<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\BankCategoryRepository;

class BankCategoryService extends Service
{
    protected $repository;

    protected $rules = [
        'create' => [
            'company_id'              => 'required',
            'main_parent_category_id' => 'required',
            'parent_category_id'      => 'required',
            'name'                    => 'required',
            'status'                  => 'required',
        ],
        'update' => [
            'company_id'              => 'required',
            'main_parent_category_id' => 'required',
            'parent_category_id'      => 'required',
            'name'                    => 'required',
            'status'                  => 'required',
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

}
