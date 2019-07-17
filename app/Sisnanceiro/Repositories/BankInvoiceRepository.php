<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\BankInvoiceTransaction;

class BankInvoiceRepository extends Repository
{
    public function __construct(BankInvoiceTransaction $modelBankInvoiceTransaction, BankInvoiceDetail $modelBankInvoiceDetail)
    {
        $this->modelBankInvoiceTransaction = $modelBankInvoiceTransaction;
        $this->modelBankInvoiceDetail      = $modelBankInvoiceDetail;
    }
}
