<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\PaymentTaxTermRepository;

class PaymentTaxTermService extends Service
{
    protected $rules = [
        'create' => [
            'payment_tax_id' => 'required|int',
            'order'          => 'required|int',
            'parcent'        => 'boolean',
            'value'          => 'required|numeric',
        ],
        'update' => [
            'payment_tax_id' => 'required|int',
            'order'          => 'required|int',
            'parcent'        => 'boolean',
            'value'          => 'required|numeric',
        ],
    ];

    public function __construct(
        Validator $validator,
        PaymentTaxTermRepository $repository
    ) {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

    public function store(array $data, $rules = false)
    {
        return parent::store($data, $rules);
    }

}
