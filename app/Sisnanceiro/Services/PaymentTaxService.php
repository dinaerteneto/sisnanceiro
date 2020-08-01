<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\PaymentTaxRepository;

class PaymentTaxService extends Service
{
    protected $rules = [
        'create' => [
            'bank_account_id'   => 'required|int',
            'payment_method_id' => 'required|int',
            'days_for_payment'  => 'required|int',
            'days_business'     => 'boolean',
        ],
        'update' => [
            'bank_account_id'   => 'required|int',
            'payment_method_id' => 'required|int',
            'days_for_payment'  => 'required|int',
            'days_business'     => 'boolean',
        ],
    ];

    public function __construct(
        Validator $validator,
        PaymentTaxRepository $repository
    ) {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

    public function store(array $data, $rules = false)
    {
        return parent::store($data, $rules);
    }

    public function getAll($search = null)
    {
        return $this->paymentTaxRepository->getAll($search);
    }

    public function findBy($column, $value)
    {
        $item = $this->repository->findBy($column, $value);
        if ($item) {
            $item->with('bank_account');
            return $item;
        } else {
            $this->validator->addError('not_found', 'id', 'No record found for this id.');
            return false;
        }
    }
}
