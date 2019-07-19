<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankInvoiceDetail;

class BankInvoiceDetailRepository extends Repository
{
    public function __construct(BankInvoiceDetail $model)
    {
        $this->model = $model;
    }
}
