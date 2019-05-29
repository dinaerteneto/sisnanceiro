<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\StoreProductBrandRepository;

class StoreProductBrandService extends Service
{
    protected $rules = [
        'create' => [
            'name' => 'required|max:255',
        ],
        'update' => [
            'name' => 'required|max:255',
        ],
    ];

    public function __construct(Validator $validator, StoreProductBrandRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

}
