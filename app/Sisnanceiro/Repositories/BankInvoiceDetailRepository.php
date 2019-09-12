<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankCategory;

class BankInvoiceDetailRepository extends Repository
{
    public function __construct(BankInvoiceDetail $model)
    {
        $this->model = $model;
    }

    /**
     * return BankInvoice of the inicial balance
     * @param BankAccount $bankAccount
     * @return BankInvoiceDetail
     */
    public function findInitialBalance(BankAccount $bankAccount) 
    {
        return $this->model
            ->where('bank_account_id', '=', $bankAccount->id)
            ->where('bank_category_id', '=', BankCategory::CATEGORY_INITIAL_BALANCE)
            ->first();   
    }
}
