<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\BankAccount;

class BankAccountTransformer extends TransformerAbstract
{
    public function transform(BankAccount $bankAccount)
    {
        $initialBalanceDate = Carbon::createFromFormat('Y-m-d', $bankAccount->initial_balance_date);
        $sendBankAccount    = false;
        if (!empty($bankAccount->bank_id) || !empty($bankAccount->agency) || !empty($bankAccount->account) || !empty($bankAccount->agency_dv) || !empty($bankAccount->account_dv)) {
            $sendBankAccount = true;
        }

        return [
            'id'                   => $bankAccount->id,
            'bank_id'              => $bankAccount->bank_id,
            'default'              => $bankAccount->default,
            'default_online'       => $bankAccount->default_online,
            'physical'             => $bankAccount->physical,
            'name'                 => $bankAccount->name,
            'bank'                 => $bankAccount->bank,
            'agency'               => $bankAccount->agency,
            'account'              => $bankAccount->account,
            'type'                 => $bankAccount->type,
            'initial_balance'      => Mask::currency($bankAccount->initial_balance),
            'initial_balance_date' => $initialBalanceDate->format('d/m/Y'),
            'agency_dv'            => $bankAccount->agency_dv,
            'account_dv'           => $bankAccount->account_dv,
            'cpf_cnpj'             => $bankAccount->cpf_cnpj,
            'legal_name'           => $bankAccount->legal_name,
            'send_bank_account'    => $sendBankAccount,
            'current_balance'      => Mask::currency($bankAccount->getCurrentBalance()),
        ];
    }
}
