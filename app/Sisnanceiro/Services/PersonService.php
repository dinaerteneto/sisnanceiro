<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\PersonRepository;

class PersonService extends Service
{
    protected $rules = [
        'create' => [
            'name'     => 'required|max:255',
            'cpf'      => 'unique',
        ],
        'update' => [
            'physical' => 'required|int',
            'name'     => 'required|int',
            'cpf'      => 'unique',
        ],
    ];

    public function __construct(Validator $validator, PersonRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

}
