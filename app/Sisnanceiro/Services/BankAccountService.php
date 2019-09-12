<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankCategory;
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

    public function __construct(
        Validator $validator,
        BankAccountRepository $repository,
        BankTransactionService $bankTransactionService,
        BankInvoiceDetailService $bankInvoiceDetailService
    ) {
        $this->validator                = $validator;
        $this->repository               = $repository;
        $this->bankTransactionService   = $bankTransactionService;
        $this->bankInvoiceDetailService = $bankInvoiceDetailService;
    }

    public function store(array $input, $rules = false)
    {
        \DB::beginTransaction();
        try {
            $data = $this->mapData($input);
            $this->resetDefault();
            $bankAccount = parent::store($data, $rules);

            $data = [
                'BankInvoiceTransaction' => [
                    'bank_category_id' => BankCategory::CATEGORY_INITIAL_BALANCE,
                    'description'      => 'Saldo inicial',
                    'note'             => 'Saldo inicial',
                    'total_invoices'   => 1,
                    'total_value'      => $input['initial_balance'],
                ],
                'BankInvoiceDetail'      => [
                    'bank_category_id' => BankCategory::CATEGORY_INITIAL_BALANCE,
                    'bank_account_id'  => $bankAccount->id,
                    'net_value'        => $input['initial_balance'],
                    'due_date'         => $input['initial_balance_date'],
                ],
            ];

            $this->bankTransactionService->store($data, 'create');
            \DB::commit();
            return $bankAccount;

        } catch (\PDOException $e) {
            \DB::rollBack();
            abort(500, 'Erro na tentativa de criar o lançamento.');
        }
    }

    public function update(Model $model, array $input, $rules = 'update')
    {
        
        try {

            $data = $this->mapData($input);
            $this->resetDefault();
            $bankAccount = parent::update($model, $data, $rules);
            $bankInvoice = $this->bankInvoiceDetailService->findInitialBalance($model);

            $data = [
                'BankInvoiceTransaction' => [
                    'id'               => $bankInvoice->bank_invoice_transaction_id,
                    'bank_category_id' => BankCategory::CATEGORY_INITIAL_BALANCE,
                    'description'      => 'Saldo inicial',
                    'note'             => 'Saldo inicial',
                    'total_invoices'   => 1,
                    'total_value'      => $input['initial_balance'],
                ],
                'BankInvoiceDetail'      => [
                    'id'               => $bankInvoice->id,
                    'bank_category_id' => BankCategory::CATEGORY_INITIAL_BALANCE,
                    'bank_account_id'  => $model->id,
                    'net_value'        => $input['initial_balance'],
                    'due_date'         => $input['initial_balance_date'],
                ],
            ];
            
            $this->bankTransactionService->updateInvoices($bankInvoice, $data);
            

            return $bankAccount;
        } catch (\Exception $e) {
          
            abort(500, 'Erro na tentativa de alterar o lançamento.');
        }
    }

    /**
     * remove flat default all acounts of the company
     * @param int companyId
     * @return int
     */
    private function resetDefault()
    {
        $companyId = Auth::user()->company_id;
        $affected  = \DB::table('bank_account')
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
