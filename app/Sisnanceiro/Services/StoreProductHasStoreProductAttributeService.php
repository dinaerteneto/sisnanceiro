<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\StoreProductHasStoreProductAttributeRepository;

class StoreProductHasStoreProductAttributeService extends Service
{

    protected $rules = [
        'create' => [
            'store_product_id'           => 'required|int',
            'store_product_attribute_id' => 'required|int',
            'value'                      => 'required',
        ],
        'update' => [
            'store_product_id'           => 'required|int',
            'store_product_attribute_id' => 'required|int',
            'value'                      => 'required',
        ],
    ];

    public function __construct(Validator $validator, StoreProductHasStoreProductAttributeRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

}
