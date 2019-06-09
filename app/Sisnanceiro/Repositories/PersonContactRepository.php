<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\PersonContact;

class PersonContactRepository extends Repository
{

    public function __construct(PersonContact $model)
    {
        $this->model = $model;
    }

}
