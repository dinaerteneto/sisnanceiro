<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\BankAccountRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        if (empty($data['send_bank_account'])) {
            $data['bank_id']    = null;
            $data['agency']     = null;
            $data['agency_dv']  = null;
            $data['account']    = null;
            $data['account_dv'] = null;
        }

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
        $this->resetDefault();
        $return = parent::store($data, $rules);
        return $return;
    }

    public function update(Model $model, array $data, $rules = 'update')
    {
        $data = $this->mapData($data);
        $this->resetDefault();
        $return = parent::update($model, $data, $rules);
        return $return;
    }

    /**
     * remove flat default all acounts of the company
     * @param int companyId
     * @return int
     */
    private function resetDefault()
    {
        $companyId = Auth::user()->company_id;
        $affected = \DB::table('bank_account')
            ->where(
                [
                    'company_id' => $companyId,
                    'deleted_at' => null,
                ]
            )
            ->update(['default' => 0]);
        return $affected;
    }

}
