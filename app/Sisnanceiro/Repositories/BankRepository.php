<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Bank;

class BankRepository extends Repository
{

    public function __construct(Bank $model)
    {
        $this->model = $model;
    }
}
