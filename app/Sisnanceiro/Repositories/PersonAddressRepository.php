<?php
namespace Sisnanceiro\Repositories;

use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Models\PersonAddress;

class PersonAddressRepository extends Repository
{

    public function __construct(PersonAddress $model)
    {
        $this->model = $model;
    }

    public function getAllType()
    {
        $companyId = Auth::user()->company_id;
        $query     = \DB::table('person_address_type')
            ->where('company_id', '=', $companyId)
            ->orWhereNull('company_id');
        return $query->get();

    }
}
