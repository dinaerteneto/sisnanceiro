<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\CustomerRepository;
use Sisnanceiro\Repositories\PersonAddressRepository;
use Sisnanceiro\Repositories\PersonContactRepository;
use Sisnanceiro\Repositories\PersonRepository;

class CustomerService extends PersonService
{
    protected $rules = [
        'create' => [
            'firstname' => 'required|max:255',
            'lastname'  => 'max:255',
            // 'cpf'       => 'unique',
        ],
        'update' => [
            'firstname' => 'required|max:255',
            'lastname'  => 'max:255',
            // 'cpf'       => 'unique',
        ],
    ];

    public function __construct(Validator $validator,
        PersonRepository $repository,
        CustomerRepository $customerRepository,
        PersonContactRepository $personContactRepository,
        PersonAddressRepository $personAddressRepository
    ) {
        $this->validator               = $validator;
        $this->repository              = $repository;
        $this->customerRepository      = $customerRepository;
        $this->personContactRepository = $personContactRepository;
        $this->personAddressRepository = $personAddressRepository;
    }

    public function mapData(array $data)
    {
        $carbonBirthdate = Carbon::createFromFormat('d/m/Y', $data['birthdate']);
        return [
            'physical'  => $data['physical'],
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'birthdate' => $carbonBirthdate->format('Y-m-d'),
            'cpf'       => preg_replace("/[^0-9]/", "", $data['cpf']),
            'rg'        => preg_replace("/[^0-9]/", "", $data['rg']),
            'gender'    => $data['gender'],
        ];
    }

    /**
     * persist customer
     * @param array $data
     * @param string $rules
     * @return Model
     */
    public function store(array $data, $rules = false)
    {
        $data  = $this->mapData($data);
        $model = parent::store($data, $rules);
        if (!isset($data['id'])) {
            $this->customerRepository->insert(['id' => $model->id]);
        }
        return $model;
    }

}
