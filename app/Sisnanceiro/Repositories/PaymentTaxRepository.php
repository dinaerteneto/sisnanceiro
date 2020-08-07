<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\PaymentTax;

class PaymentTaxRepository extends Repository
{
    public function __construct(PaymentTax $model)
    {
        $this->model = $model;
    }

}
