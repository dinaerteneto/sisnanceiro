<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Customer;

class CustomerRepository extends Repository
{

    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

}
