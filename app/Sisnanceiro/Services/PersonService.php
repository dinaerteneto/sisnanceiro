<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\PersonAddressRepository;
use Sisnanceiro\Repositories\PersonContactRepository;
use Sisnanceiro\Repositories\PersonRepository;
use Sisnanceiro\Models\Person;

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

    private $personContactRepository;
    private $personAddressRepository;

    public function __construct(
        Validator $validator,
        PersonRepository $repository,
        PersonContactRepository $personContactRepository,
        PersonAddressRepository $personAddressRepository
    ) {
        $this->validator               = $validator;
        $this->repository              = $repository;
        $this->personContactRepository = $personContactRepository;
        $this->personAddressRepository = $personAddressRepository;
    }

    public function store(array $data, $rules = false)
    {
        return parent::store($data, $rules);
    }

    /**
     * add address of the person
     * @param array $data
     * @return Model
     */
    public function storeAddress(Person $person, array $data)
    {
        $data['person_id'] = $person->id;
        return $this->personAddressRepository->create($data);
    }

    /**
     * add contact of the person
     * @param array $data
     * @return Model
     */
    public function storeContact(Person $person, array $data)
    {
        $data['person_id'] = $person->id;
        return $this->personContactRepository->create($data);
    }

}
