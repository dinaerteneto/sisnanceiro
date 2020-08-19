<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Repositories\BankInvoiceTransactionRepository;
use Sisnanceiro\Services\BankAccountService;
use Sisnanceiro\Services\BankTransactionService;

class BankTransactionTransferService extends Service
{
    protected $rules = [
        'create' => [
            'bank_category_id'       => 'required',
            'bank_account_source_id' => 'required',
            'bank_account_target_id' => 'required',
            'due_date'               => 'required',
        ],
        'update' => [
            'bank_category_id'       => 'required',
            'bank_account_source_id' => 'required',
            'bank_account_target_id' => 'required',
            'due_date'               => 'required',
        ],
    ];

    public function __construct(
        Validator $validator,
        BankInvoiceTransactionRepository $repository,
        BankInvoiceDetailService $bankInvoiceDetailService,
        BankAccountService $bankAccountService,
        BankTransactionService $bankTransactionService
    ) {
        $this->validator                = $validator;
        $this->repository               = $repository;
        $this->bankInvoiceDetailService = $bankInvoiceDetailService;
        $this->bankTransactionService   = $bankTransactionService;
        $this->bankAccountService       = $bankAccountService;
    }

    private function mapData(array $data = [], $bankAccountSource, $bankAccountTarget)
    {
        $dataDetail      = $data['BankInvoiceDetail'];
        $dataTransaction = $data['BankInvoiceTransaction'];

        $description = "Transferência enviada da conta: {$bankAccountSource->name} para a conta: {$bankAccountTarget->name}";

        $netValue = FloatConversor::convert($dataDetail['net_value']);
        $ret      = array_merge($dataDetail, [
            'is_transfer'             => true,
            'bank_account_source_id'  => $bankAccountSource->id,
            'bank_account_target_id'  => $bankAccountTarget->id,
            'total_invoices'          => 1,
            'total_value'             => $netValue,
            'net_value'               => $netValue,
            'main_parent_category_id' => BankCategory::CATEGORY_TRANSFER,
            'description'             => $description,
            'note'                    => $dataTransaction['note'],
        ]);

        return $ret;
    }

    private function mapDataDetail(array $data = [], $transaction, $type)
    {
        $dueDate  = null;
        $netValue = null;
        $dueDate  = Carbon::createFromFormat('d/m/Y', $data['due_date']);
        $dueDate  = $dueDate->format('Y-m-d');
        $netValue = FloatConversor::convert($data['net_value']);

        $bankAccountId  = $data['bank_account_target_id'];
        $bankCategoryId = BankCategory::CATEGORY_TRANSFER_IN;
        if ($type == 'source') {
            $bankAccountId  = $data['bank_account_source_id'];
            $bankCategoryId = BankCategory::CATEGORY_TRANSFER_OUT;
            $netValue       = $netValue * -1;
        }

        $ret = array_merge($data, [
            'bank_invoice_transaction_id' => $transaction->id,
            'bank_category_id'            => $bankCategoryId,
            'bank_account_id'             => $bankAccountId,
            'due_date'                    => $dueDate,
            'competence_date'             => $dueDate,
            'payment_date'                => $dueDate,
            'net_value'                   => $netValue,
            'gross_value'                 => $netValue,
            'parcel_number'               => 1,
            'status'                      => BankInvoiceDetail::STATUS_PAID,
        ]);

        return $ret;
    }

    public function store(array $input, $rules = false)
    {
        \DB::beginTransaction();
        try {
            $dataDetail = $input['BankInvoiceDetail'];

            $bankAccountSource = $this->bankAccountService->find($dataDetail['bank_account_source_id']);
            $bankAccountTarget = $this->bankAccountService->find($dataDetail['bank_account_target_id']);

            $dataTransactionSource = $this->mapData($input, $bankAccountSource, $bankAccountTarget);
            $recordTransaction     = parent::store($dataTransactionSource, 'create');

            $mapSource = $this->mapDataDetail($input['BankInvoiceDetail'], $recordTransaction, 'source');
            $mapTarget = $this->mapDataDetail($input['BankInvoiceDetail'], $recordTransaction, 'target');

            $source = $this->bankInvoiceDetailService->store($mapSource, 'create');
            $target = $this->bankInvoiceDetailService->store($mapTarget, 'create');

            if (method_exists($source, 'getErrors') && $source->getErrors()) {
                throw new \Exception('Erro na tentativa de incluir transferência na origem.', 500);
            }
            if (method_exists($target, 'getErrors') && $target->getErrors()) {
                throw new \Exception('Erro na tentativa de incluir transferência no destino.', 500);
            }
            \DB::commit();

        } catch (\PDOException $e) {
            dd($e);
            \DB::rollBack();
            abort(500, 'Erro na tentativa de criar a transferência.');
        }
    }

    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            $invoice       = $this->bankInvoiceDetailService->find($id);
            $transactionId = $invoice->bank_invoice_transaction_id;

            $this->bankInvoiceDetailService
                ->repository
                ->where('bank_invoice_transaction_id', $transactionId)
                ->delete();

            $this->bankTransactionService
                ->repository
                ->where('id', $transactionId)
                ->delete();

            \DB::commit();

            return true;

        } catch (\PDOException $e) {
            \DB::rollBack();
            abort(500, 'Erro na tentativa de excluir a transferência.');
        }
    }

    public function update(Model $model, array $input, $rules = 'update')
    {
        \DB::beginTransaction();
        try {
            $dataDetail = $input['BankInvoiceDetail'];

            $bankAccountSource = $this->bankAccountService->find($dataDetail['bank_account_source_id']);
            $bankAccountTarget = $this->bankAccountService->find($dataDetail['bank_account_target_id']);

            $dataTransactionSource = $this->mapData($input, $bankAccountSource, $bankAccountTarget);
            $recordTransaction     = parent::update($model, $dataTransactionSource, 'update');

            $mapSource = $this->mapDataDetail($input['BankInvoiceDetail'], $model, 'source');
            $mapTarget = $this->mapDataDetail($input['BankInvoiceDetail'], $model, 'target');

            $transactionId = $model->id;

            $invoiceSource = $this->bankInvoiceDetailService
                ->repository
                ->where('bank_invoice_transaction_id', $transactionId)
                ->where('bank_category_id', BankCategory::CATEGORY_TRANSFER_OUT)
                ->first();

            $invoiceTarget = $this->bankInvoiceDetailService
                ->repository
                ->where('bank_invoice_transaction_id', $transactionId)
                ->where('bank_category_id', BankCategory::CATEGORY_TRANSFER_IN)
                ->first();

            $source = $this->bankInvoiceDetailService->update($invoiceSource, $mapSource, 'update');
            $target = $this->bankInvoiceDetailService->update($invoiceTarget, $mapTarget, 'update');

            if (method_exists($source, 'getErrors') && $source->getErrors()) {
                throw new \Exception('Erro na tentativa de alterar transferência na origem.', 500);
            }
            if (method_exists($target, 'getErrors') && $target->getErrors()) {
                throw new \Exception('Erro na tentativa de alterar transferência no destino.', 500);
            }
            \DB::commit();

            return true;
        } catch (\PDOException $e) {
            dd($e);
            \DB::rollBack();
            abort(500, 'Erro na tentativa de alterar a transferência.');
        }
    }

    public function getAll(array $search = [])
    {
        return $this->repository->getAll($search);
    }

}
