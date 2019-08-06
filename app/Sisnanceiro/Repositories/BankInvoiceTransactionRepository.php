<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\BankInvoiceTransaction;

class BankInvoiceTransactionRepository extends Repository
{
    public function __construct(
        BankInvoiceTransaction $model, 
        BankInvoiceDetail $bankInvoiceDetail
        )
    {
        $this->model             = $model;
        $this->bankInvoiceDetail = $bankInvoiceDetail;
    }

/*     public function find($id) {
        return $this->bankInvoiceDetail->find($id);
    }
 */
    public function getAll($search = null)
    {
        return $this->bankInvoiceDetail
            ->load('company', 'transaction', 'account', 'category', 'customer', 'supplier')
            ->all();
    }
}
