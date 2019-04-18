<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Person;

class PersonRepository extends Repository
{
    public function __construct(Person $model)
    {
        $this->model = $model;
    }

}
