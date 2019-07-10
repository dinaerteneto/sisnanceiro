<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankAccount;

class BankAccountRepository extends Repository {

    public function __construct(BankAccount $model)
    {
        $this->model = $model;

    }
}