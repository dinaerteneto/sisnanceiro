<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\CustomerRepository;
use Sisnanceiro\Repositories\PersonAddressRepository;
use Sisnanceiro\Repositories\PersonContactRepository;
use Sisnanceiro\Repositories\PersonRepository;
use Illuminate\Support\Facades\Auth;

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

    public function getAll()
    {
        $companyId = Auth::user()->company_id;
        $sql = "
            select p.id
                 , p.physical
                 , p.firstname
                 , p.lastname
                 , email.value as email
                 , cellphone.value as cellphone
                 , phone.value as phone
             from person p
             join customer c
               on p.id = c.id
             left join person_contact email on email.id = (
                select min(id)
                  from person_contact 
                 where person_id = p.id
                   and person_contact_type_id = 1
                 limit 1
            )
            left join person_contact cellphone on cellphone.id = (
               select min(id)
                 from person_contact
                where person_id = p.id
                  and person_contact_type_id = 2
            )
            left join person_contact phone on phone.id = (
               select min(id)
                from person_contact
               where person_id = p.id
                 and person_contact_type_id = 3
            )
          where p.company_id = {$companyId}
        ";
        return \DB::select($sql);
    }

}
