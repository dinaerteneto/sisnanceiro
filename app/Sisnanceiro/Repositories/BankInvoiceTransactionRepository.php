<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankInvoiceTransaction;

class BankInvoiceTransactionRepository extends Repository
{
    public function __construct(BankInvoiceTransaction $model)
    {
        $this->model = $model;
    }
}
