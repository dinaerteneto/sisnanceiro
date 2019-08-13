<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankAccount;
use Sisnanceiro\Repositories\BankInvoiceDetailRepository;

class BankInvoiceDetailService extends Service
{

    protected $rules = [
        'create' => [
            'bank_category_id'            => 'required',
            'bank_account_id'             => 'required',
            'bank_invoice_transaction_id' => 'required',
            'gross_value'                 => 'required|numeric',
            'net_value'                   => 'required|numeric',
            'parcel_number'               => 'required',
            'due_date'                    => 'required',
            // 'status'                      => 'required',
        ],
        'update' => [
            'bank_category_id'            => 'required',
            // 'bank_account_id'             => 'required',
            'bank_invoice_transaction_id' => 'required',
            // 'gross_value'                 => 'required|numeric',
            // 'net_value'                   => 'required|numeric',
            // 'parcel_number'               => 'required',
            // 'due_date'                    => 'required',
            // 'status'                      => 'required',
        ],
    ];

    public function __construct(Validator $validator, BankInvoiceDetailRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

    /**
     * return BankInvoice of the inicial balance
     * @param BankAccount $bankAccount
     * @return BankInvoiceDetail
     */
    public function findInitialBalance(BankAccount $bankAccount)
    {
        return $this->repository->findInitialBalance($bankAccount);
    }

}
