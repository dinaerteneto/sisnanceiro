<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\CompanyRepository;
use Sisnanceiro\Services\PersonService;
use Sisnanceiro\Services\UserService;

class CompanyService extends Service
{

    protected $rules = [
        'create' => [
            'id' => 'required|int',
        ],
        'update' => [
            'id' => 'required|int',
        ],
    ];

    protected $repository;

    public function __construct(
        Validator $validator,
        CompanyRepository $companyRepository,
        PersonService $personService,
        UserService $userService
    ) {
        $this->validator     = $validator;
        $this->repository    = $companyRepository;
        $this->personService = $personService;
        $this->userService   = $userService;
    }

    /**
     * Service create company, person and user for company
     * @param array data
     * @return array
     */
    public function register(array $data)
    {
        $companyData = [
            'firstname' => $data['company_name'],
            'phisycal'  => (int) 0,
        ];

        $companyPersonService = $this->personService->store($companyData, 'create');
        $repository           = $this->repository->insert(['id' => $companyPersonService->id]);

        $personData = [
            'company_id' => $companyPersonService->id,
            'firstname'  => $data['firstname'],
            'lastname'   => $data['lastname'],
            'gender'     => $data['gender'],
            'email'      => $data['email'],
            'phisycal'   => 1,
        ];
        $person = $this->personService->store($personData, 'create');
        $this->userService->create($person);

        return $repository;
    }

}
