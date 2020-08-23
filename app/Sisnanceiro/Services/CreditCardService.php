<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\CreditCardRepository;

class CreditCardService extends Service
{

    protected $rules = [
        'create' => [
            'bank_account_id'      => 'required|int',
            'credit_card_brand_id' => 'required|int',
            'name'                 => 'required|string',
            'limit'                => 'required|numeric',
            'payment_day'          => 'required|int',
            'closing_day'          => 'required|int',
        ],
        'update' => [
            'bank_account_id'      => 'required|int',
            'credit_card_brand_id' => 'required|int',
            'name'                 => 'required|string',
            'limit'                => 'required|numeric',
            'payment_day'          => 'required|int',
            'closing_day'          => 'required|int',
        ],
    ];

    protected $repository;

    public function __construct(
        Validator $validator,
        CreditCardRepository $creditCardRepository
    ) {
        $this->validator  = $validator;
        $this->repository = $creditCardRepository;
    }

}
