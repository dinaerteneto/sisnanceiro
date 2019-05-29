<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\StoreProductAttributeRepository;

class StoreProductAttributeService extends Service
{
    protected $rules = [
        'create' => [
            'name' => 'required|max:255',
        ],
        'update' => [
            'name' => 'required|max:255',
        ],
    ];

    public function __construct(Validator $validator, StoreProductAttributeRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

}
