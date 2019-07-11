<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\BankAccountRepository;

class BankAccountService extends Service
{

    protected $rules = [
        'create' => [
            // 'bank_id'              => 'int',
            // 'default'              => 'int',
            'legal_name'           => 'string',
            'initial_balance'      => 'required|numeric',
            'initial_balance_date' => 'required|string',
            'physical'             => 'required|int',
            'cpf_cnpj'             => 'numeric',
            // 'account'              => 'int',
            // 'agency'               => 'int',
            // 'account_dv'           => 'int',
            // 'agency_dv'            => 'int',
        ],
        'update' => [
            // 'bank_id'              => 'int',
            // 'default'              => 'int',
            'legal_name'           => 'string',
            'initial_balance'      => 'required|numeric',
            'initial_balance_date' => 'required|string',
            'physical'             => 'required|int',
            'cpf_cnpj'             => 'numeric',
            // 'account'              => 'int',
            // 'agency'               => 'int',
            // 'account_dv'           => 'int',
            // 'agency_dv'            => 'int',
        ],
    ];

    private function mapData(array $data)
    {
        $carbonInitialBalanceDate = Carbon::createFromFormat('d/m/Y', $data['initial_balance_date']);
        $initialBalanceDate       = $carbonInitialBalanceDate->format('Y-m-d');

        return array_merge($data, [
            'cpf_cnpj'             => preg_replace('/[^0-9]/', '', $data['cpf_cnpj']),
            'initial_balance'      => FloatConversor::convert($data['initial_balance']),
            'initial_balance_date' => $initialBalanceDate,
        ]);
    }

    public function __construct(Validator $validator, BankAccountRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

    public function store(array $input, $rules = false)
    {
        $data = $this->mapData($input);
        return parent::store($data, $rules);
    }

}
