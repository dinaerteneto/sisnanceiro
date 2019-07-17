<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\BankInvoiceRepository;

class BankTransactionService extends Service
{

    public function __construct(Validator $validator, BankInvoiceRepository $bankInvoiceRepository)
    {
        $this->validator             = $validator;
        $this->bankInvoiceRepository = $bankInvoiceRepository;
    }

    private function mapData(array $data = [])
    {
        
    }

}
