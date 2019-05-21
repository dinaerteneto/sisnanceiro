<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\PersonRepository;

class PersonService extends Service
{
    protected $rules = [
        'create' => [
            'firstname' => 'required|max:255',
            'lastname'  => 'max:255',
            'cpf'       => 'unique',
        ],
        'update' => [
            'firstname' => 'required|max:255',
            'lastname'  => 'max:255',
            'cpf'       => 'unique',
        ],
    ];

    public function __construct(Validator $validator, PersonRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

}
