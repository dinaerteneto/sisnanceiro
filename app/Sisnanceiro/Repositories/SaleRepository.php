<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Sale;

class SaleRepository extends Repository
{
    public function __construct(Sale $model)
    {
        $this->model = $model;
    }
}
