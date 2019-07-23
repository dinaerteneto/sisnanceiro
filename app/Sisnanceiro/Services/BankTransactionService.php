<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankInvoiceTransaction;
use Sisnanceiro\Repositories\BankInvoiceTransactionRepository;
use Sisnanceiro\Services\BankInvoiceDetailService;

class BankTransactionService extends Service
{

    protected $rules = [
        'create' => [
            'bank_category_id' => 'required',
            'total_invoice'    => 'required|int|min:1',
            'total_value'      => 'required|numeric',
            'type_cycle'       => 'required',
        ],
        'update' => [
            'bank_category_id' => 'required',
            'total_invoice'    => 'required|int',
            'total_value'      => 'required|numeric',
        ],
    ];

    public function __construct(Validator $validator, BankInvoiceTransactionRepository $repository, BankInvoiceDetailService $bankInvoiceDetailService)
    {
        $this->validator                = $validator;
        $this->repository               = $repository;
        $this->bankInvoiceDetailService = $bankInvoiceDetailService;
    }

    private function mapData(array $data = [])
    {
        $dataTransaction = $data['BankInvoiceTransaction'];
        $dataDetail      = $data['BankInvoiceDetail'];

        $totalInvoices = isset($dataTransaction['total_invoice']) && !empty($dataTransaction['total_invoice']) ? (int) $dataTransaction['total_invoice'] : 1;
        $netValue      = FloatConversor::convert($dataDetail['net_value']);

        return array_merge($dataTransaction, $dataDetail, [
            'description'    => nl2br($dataTransaction['description']),
            'note'           => nl2br($dataTransaction['note']),
            'total_invoices' => $totalInvoices,
            'total_value'    => $totalInvoices * $netValue,
            'net_value'      => FloatConversor::convert($dataDetail['net_value']),
            'type_cycle'     => isset($dataTransaction['type_cycle']) ? $dataTransaction['type_cycle'] : 0,
        ]);
    }

    private function mapDataDetail(array $data = [], $transactionId)
    {
        $dueDate = Carbon::createFromFormat('d/m/Y', $data['due_date']);
        return array_merge($data, [
            'bank_invoice_transaction_id' => $transactionId,
            'due_date'                    => $dueDate->format('Y-m-d'),
            'net_value'                   => FloatConversor::convert($data['net_value']),
            'gross_value'                 => FloatConversor::convert($data['net_value']),
        ]);
    }

    public function store(array $input, $rules = false)
    {
        \DB::beginTransaction();
        try {
            $details           = [];
            $dataTransaction   = $this->mapData($input);
            $recordTransaction = parent::store($dataTransaction, $rules);

            $dataDetail   = $this->mapDataDetail($input['BankInvoiceDetail'], $recordTransaction['id']);
            $firstDueDate = $dataDetail['due_date'];
            for ($parcelNumber = 1; $parcelNumber <= (int) $dataTransaction['total_invoices']; $parcelNumber++) {
                if ($parcelNumber > 1) {
                    $dueDate                = $this->setNewDueDateTypeCycle($dataTransaction['type_cycle'], $firstDueDate, ($parcelNumber - 1));
                    $dataDetail['due_date'] = $dueDate->format('Y-m-d');
                }
                $details[] = $this->addInvoice($dataDetail, $parcelNumber);
            }
            \DB::commit();
            return $details;

        } catch (\PDOException $e) {
            \DB::rollBack();
            abort(500, 'Erro na tentativa de criar o lançamento.');
        }

    }

    /**
     * set new date based on $type cycle and parcel number
     * @param int $typeCycle = type cycle selected of user
     * @param string $dueDate = due date of invoice
     * @param int $parcelNumber = parcel number of invoice
     * @return Carbon\Carbon
     */
    private function setNewDueDateTypeCycle($typeCycle, $dueDate, $parcelNumber)
    {
        $carbonDate = Carbon::parse($dueDate);
        switch ($typeCycle) {
            case BankInvoiceTransaction::TYPE_CYCLE_DIALY:
                $dueDate = $carbonDate->addDays(1 * $parcelNumber);
                break;
            case BankInvoiceTransaction::TYPE_CYCLE_WEEKLY:
                $dueDate = $carbonDate->addWeeks(1 * $parcelNumber);
                break;
            case BankInvoiceTransaction::TYPE_CYCLE_MONTHLY:
                $dueDate = $carbonDate->addMonthsNoOverflow(1 * $parcelNumber);
                break;
            case BankInvoiceTransaction::TYPE_CYCLE_YEARLY:
                $dueDate = $carbonDate->addMonthsNoOverflow(12 * $parcelNumber);
                break;
        }
        return $dueDate;
    }

    /**
     * store invoice
     * @param array $data
     * @param int $parcelNumber
     * @return Model
     */
    private function addInvoice(array $data = [], $parcelNumber = 1)
    {
        $data = array_merge($data, ['parcel_number' => $parcelNumber]);
        return $this->bankInvoiceDetailService->store($data, 'create');
    }

    public function getAll($search = null)
    {
        return $this->repository->getAll($search);
    }

}
