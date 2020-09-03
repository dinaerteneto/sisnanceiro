<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;

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

    public function findCreditCardByDueDate($creditCardId, $dueDate)
    {
        $companyId = \Auth::user()->company_id;
        $query     = \DB::query()
            ->selectRaw("
                bank_invoice_detail.*
            ")
            ->from('bank_invoice_detail')
            ->join('bank_invoice_transaction', \DB::raw('bank_invoice_detail.bank_invoice_transaction_id'), '=', \DB::raw('bank_invoice_transaction.id'))
            ->where('bank_invoice_detail.company_id', '=', $companyId)
            ->where('bank_invoice_transaction.credit_card_id', '=', $creditCardId)
            ->where('bank_invoice_transaction.is_credit_card_invoice', '=', true)
            ->where('bank_invoice_transaction.credit_card_due_date', '=', $dueDate)
            ->whereNull('bank_invoice_detail.deleted_at');
        return $query->first();
    }
}
