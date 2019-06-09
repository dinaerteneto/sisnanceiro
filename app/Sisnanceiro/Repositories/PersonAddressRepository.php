<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\PersonAddress;

class PersonAddressRepository extends Repository
{

    public function __construct(PersonAddress $model)
    {
        $this->model = $model;
    }

}
