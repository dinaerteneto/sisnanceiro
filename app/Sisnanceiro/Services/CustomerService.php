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
        $carbonBirthdate = isset($data['birthdate']) && !empty($data['birthdate']) ? Carbon::createFromFormat('d/m/Y', $data['birthdate']) : null;
        return [
            'id'        => isset($data['id']) && !empty($data['id']) ? $data['id'] : null,
            'physical'  => isset($data['physical']) ? $data['physical'] : true,
            'firstname' => $data['firstname'],
            'lastname'  => isset($data['lastname']) ? $data['lastname'] : null,
            'birthdate' => !empty($carbonBirthdate) ? $carbonBirthdate->format('Y-m-d') : null,
            'cpf'       => !empty($data['cpf']) ? preg_replace("/[^0-9]/", "", $data['cpf']) : null,
            'rg'        => !empty($data['rg']) ? preg_replace("/[^0-9]/", "", $data['rg']) : null,
            'gender'    => !empty($data['gender']) ? $data['gender'] : null,
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

        \DB::beginTransaction();

        try {

            $data  = $this->mapData($input['Customer']);
            $model = parent::store($data, $rules);

            if (!isset($data['id'])) {
                $this->customerRepository->insert(['id' => $model->id]);
            }
            $addressIds = [];
            if (isset($input['PersonAddress'])) {
                foreach ($input['PersonAddress'] as $keyCustomerAddress => $postCustomerAddress) {
                    $address      = $this->personService->storeAddress($model, $postCustomerAddress);
                    $addressIds[] = $address->id;
                }
            }
            $contactIds = [];
            if (isset($input['PersonContact'])) {
                foreach ($input['PersonContact'] as $keyCustomerContact => $postCustomerContact) {
                    $contact      = $this->personService->storeContact($model, $postCustomerContact);
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
            \DB::commit();
            return $model;

        } catch (\PDOException $e) {
            \DB::rollBack();
            abort(500, 'Erro na tentativa.');
        }

    }

    public function getAll($search = null)
    {
        return $this->customerRepository->getAll($search);
    }

}
