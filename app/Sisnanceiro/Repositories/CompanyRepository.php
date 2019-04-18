<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Company;

class CompanyRepository extends Repository {

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

}