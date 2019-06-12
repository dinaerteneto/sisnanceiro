<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\CustomerRepository;
use Sisnanceiro\Repositories\PersonAddressRepository;
use Sisnanceiro\Repositories\PersonContactRepository;
use Sisnanceiro\Repositories\PersonRepository;
use Sisnanceiro\Services\PersonService;

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
        PersonAddressRepository $personAddressRepository,
        PersonService $personService
    ) {
        $this->validator               = $validator;
        $this->repository              = $repository;
        $this->customerRepository      = $customerRepository;
        $this->personContactRepository = $personContactRepository;
        $this->personAddressRepository = $personAddressRepository;
        $this->personService           = $personService;
    }

    public function mapData(array $data)
    {
        $carbonBirthdate = Carbon::createFromFormat('d/m/Y', $data['birthdate']);
        return [
            'id'        => isset($data['id']) && !empty($data['id']) ? $data['id'] : null,
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
     * @param array $input
     * @param string $rules
     * @return Model
     */
    public function store(array $input, $rules = false)
    {
        $data  = $this->mapData($input['Customer']);
        $model = parent::store($data, $rules);

        if (!isset($data['id'])) {
            $this->customerRepository->insert(['id' => $model->id]);
        }
        if (isset($input['PersonAddress'])) {
            foreach ($input['PersonAddress'] as $keyCustomerAddress => $postCustomerAddress) {
                $address = $this->personService->storeAddress($model, $postCustomerAddress);
                $addressIds[] = $address->id; 
            }
        }
        if (isset($input['PersonContact'])) {
            foreach ($input['PersonContact'] as $keyCustomerContact => $postCustomerContact) {
                $contact = $this->personService->storeContact($model, $postCustomerContact);
                $contactIds[] = $contact->id;
            }
        }

        // remove addresses removed on frontend
        $unsetAddresses = $this->personAddressRepository
            ->select('id')
            ->where('person_id', '=', $model->id)
            ->whereNotIn('id', $addressIds)
            ->get()
            ->toArray();
        if ($unsetAddresses) {
            $this->personAddressRepository->destroy($unsetAddresses);
        }
        // remove contacts removed on frontend
        print_r($contactIds);
        $unsetContacts = $this->personContactRepository
            ->select('id')
            ->where('person_id', '=', $model->id)
            ->whereNotIn('id', $contactIds)
            ->get()
            ->toArray();
        if ($unsetContacts) {
            $this->personContactRepository->destroy($unsetContacts);
        }

        return $model;
    }

    public function getAll($search = null)
    {
        return $this->customerRepository->getAll($search);
    }

}
