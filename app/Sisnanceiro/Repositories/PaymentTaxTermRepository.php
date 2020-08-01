<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\PaymentTaxTerm;

class PaymentTaxTermRepository extends Repository
{
    public function __construct(PaymentTaxTerm $model)
    {
        $this->model = $model;
    }
}
