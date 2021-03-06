<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\Person;
use Sisnanceiro\Repositories\PersonAddressRepository;
use Sisnanceiro\Repositories\PersonContactRepository;
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
        $data['person_address_type_id'] = !empty($data['person_address_type_id']) ? $data['person_address_type_id'] : 1;
        if (isset($data['id']) && (!empty($data['id']) && is_numeric($data['id']))) {
            $model = $this->personAddressRepository->find($data['id']);
            $model->update($data);
            return $model;
        }
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
        $data['person_contact_type_id'] = !empty($data['person_contact_type_id']) ? $data['person_contact_type_id'] : 1;
        if (isset($data['id']) && (!empty($data['id']) && is_numeric($data['id']))) {
            $model = $this->personContactRepository->find($data['id']);
            $model->update($data);
            return $model;
        }
        return $this->personContactRepository->create($data);
    }

    public function getTypeContacts()
    {
        return $this->personContactRepository->getAllType();
    }

    public function getTypeAddresses()
    {
        return $this->personAddressRepository->getAllType();
    }

}
