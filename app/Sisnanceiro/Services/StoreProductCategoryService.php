<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\StoreProductCategoryRepository;

class StoreProductCategoryService extends Service
{
    protected $rules = [
        'create' => [
            'name' => 'required|max:255',
        ],
        'update' => [
            'name' => 'required|max:255',
        ],
    ];

    public function __construct(Validator $validator,  StoreProductCategoryRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

}
