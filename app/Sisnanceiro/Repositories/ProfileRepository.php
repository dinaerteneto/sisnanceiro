<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Person;

class ProfileRepository extends Repository
{

    public function __construct(Person $model)
    {
        $this->model = $model;
    }

}
