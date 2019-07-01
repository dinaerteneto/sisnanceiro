<?php
namespace Sisnanceiro\Repositories;

use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Models\Customer;

class CustomerRepository extends Repository
{

    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    /**
     * get all customers
     * @param String $search
     * @return Illuminate\Database\Query\Builder
     */    
    public function getAll($search)
    {
        $companyId = Auth::user()->company_id;
        $query     = \DB::query()
            ->selectRaw("
                  person.id
                , case person.physical 
                  WHEN 1 THEN 'Física' ELSE 'Jurídica' 
                   END physical
                , person.firstname
                , person.lastname
                , (
                    select value
                      from person_contact
                     where person_contact.person_id = person.id
                       and person_contact.person_contact_type_id = 1
                       and person_contact.deleted_at is null
                     limit 1
                ) as email
                , (
                    select value
                      from person_contact
                     where person_contact.person_id = person.id
                       and person_contact.person_contact_type_id = 2
                       and person_contact.deleted_at is null
                     limit 1
                ) as cellphone
                , (
                    select value
                      from person_contact
                     where person_contact.person_id = person.id
                       and person_contact.person_contact_type_id = 3
                       and person_contact.deleted_at is null
                     limit 1
                ) as phone
            ")
            ->from('person')
            ->join('customer', 'customer.id', '=', 'person.id')
            ->where('person.company_id', '=', $companyId)
            ->whereNull('person.deleted_at');
        if (!empty($search)) {
            $query->where(\DB::raw("person.firstname LIKE '%{$search}%'"));
        }
        return $query;
    }

}
