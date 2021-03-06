<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\SupplierRepository;
use Sisnanceiro\Repositories\PersonAddressRepository;
use Sisnanceiro\Repositories\PersonContactRepository;
use Sisnanceiro\Repositories\PersonRepository;
use Sisnanceiro\Services\PersonService;

class SupplierService extends PersonService
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
        SupplierRepository $supplierRepository,
        PersonContactRepository $personContactRepository,
        PersonAddressRepository $personAddressRepository,
        PersonService $personService
    ) {
        $this->validator               = $validator;
        $this->repository              = $repository;
        $this->supplierRepository      = $supplierRepository;
        $this->personContactRepository = $personContactRepository;
        $this->personAddressRepository = $personAddressRepository;
        $this->personService           = $personService;
    }

    public function mapData(array $data)
    {
        $carbonBirthdate = !empty($data['birthdate']) ? Carbon::createFromFormat('d/m/Y', $data['birthdate']) : null;
        return [
            'id'        => isset($data['id']) && !empty($data['id']) ? $data['id'] : null,
            'physical'  => isset($data['physical']) ? $data['physical'] : false,
            'firstname' => $data['firstname'],
            'lastname'  => isset($data['lastname']) ? $data['lastname'] : null,
            'birthdate' => !empty($carbonBirthdate) ? $carbonBirthdate->format('Y-m-d') : null,
            'cpf'       => !empty($data['cpf']) ? preg_replace("/[^0-9]/", "", $data['cpf']) : null,
            'rg'        => !empty($data['rg']) ? preg_replace("/[^0-9]/", "", $data['rg']) : null,
            'gender'    => !empty($data['gender']) ? $data['gender'] : null,
        ];
    }

    /**
     * persist Supplier
     * @param array $input
     * @param string $rules
     * @return Model
     */
    public function store(array $input, $rules = false)
    {
        $data  = $this->mapData($input['Supplier']);
        $model = parent::store($data, $rules);

        if (!isset($data['id'])) {
            $this->supplierRepository->insert(['id' => $model->id]);
        }
        $addressIds = [];
        if (isset($input['PersonAddress'])) {
            foreach ($input['PersonAddress'] as $keySupplierAddress => $postSupplierAddress) {
                $address      = $this->personService->storeAddress($model, $postSupplierAddress);
                $addressIds[] = $address->id;
            }
        }
        $contactIds = [];
        if (isset($input['PersonContact'])) {
            foreach ($input['PersonContact'] as $keySupplierContact => $postSupplierContact) {
                $contact      = $this->personService->storeContact($model, $postSupplierContact);
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
        return $this->supplierRepository->getAll($search);
    }

}
