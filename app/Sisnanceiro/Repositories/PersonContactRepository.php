<?php
namespace Sisnanceiro\Repositories;

use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Models\PersonContact;

class PersonContactRepository extends Repository
{

    protected $modelPersonContactType;

    public function __construct(PersonContact $model)
    {
        $this->model = $model;
    }

    public function getAllType()
    {
        $companyId = Auth::user()->company_id;
        $query     = \DB::table('person_contact_type')
            ->where('company_id', '=', $companyId)
            ->orWhereNull('company_id');
        return $query->get();
    }

}
