<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\CreditCard;

class CreditCardRepository extends Repository
{
    public function __construct(CreditCard $model)
    {
        $this->model = $model;
    }

}
