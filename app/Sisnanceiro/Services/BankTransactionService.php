<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\BankInvoiceTransaction;
use Sisnanceiro\Repositories\BankInvoiceDetailRepository;
use Sisnanceiro\Repositories\BankInvoiceTransactionRepository;
use Sisnanceiro\Services\BankInvoiceDetailService;

class BankTransactionService extends Service
{

    const OPTION_ONLY_THIS   = 1;
    const OPTION_THIS_FUTURE = 2;
    const OPTION_ALL         = 3;
    const OPTION_ALL_PENDENT = 4;

    protected $rules = [
        'create' => [
            'bank_category_id' => 'required',
            'total_invoices'   => 'required|int|min:1',
            'total_value'      => 'required|numeric',
            'type_cycle'       => 'required',
        ],
        'update' => [
            'bank_category_id' => 'required',
            // 'total_invoices'   => 'required|int',
            // 'total_value'      => 'required|numeric',
        ],
    ];

    public function __construct(
        Validator $validator,
        BankInvoiceTransactionRepository $repository,
        BankInvoiceDetailService $bankInvoiceDetailService,
        BankInvoiceDetailRepository $bankInvoiceRepository,
        BankCategoryService $bankCategoryService
    ) {
        $this->validator                = $validator;
        $this->repository               = $repository;
        $this->bankInvoiceRepository    = $bankInvoiceRepository;
        $this->bankInvoiceDetailService = $bankInvoiceDetailService;
        $this->bankCategoryService      = $bankCategoryService;
    }

    private function mapData(array $data = [])
    {
        $dataTransaction = $data['BankInvoiceTransaction'];
        $dataDetail      = $data['BankInvoiceDetail'];

        $totalInvoices = isset($dataTransaction['total_invoice']) && !empty($dataTransaction['total_invoice']) ? (int) $dataTransaction['total_invoice'] : 1;

        $netValue   = null;
        $totalValue = null;
        if (isset($dataDetail['net_value'])) {
            $netValue = FloatConversor::convert($dataDetail['net_value']);
            if (isset($dataDetail['main_parent_category_id']) && ($dataDetail['main_parent_category_id'] == BankCategory::CATEGORY_TO_PAY)) {
                $netValue *= -1;
            }
            $totalValue = $totalInvoices * $netValue;
        }

        $ret = array_merge($dataTransaction, $dataDetail, [
            'description'    => nl2br($dataTransaction['description']),
            'note'           => nl2br($dataTransaction['note']),
            'total_invoices' => $totalInvoices,
            'total_value'    => $totalValue,
            'net_value'      => $netValue,
            'type_cycle'     => isset($dataTransaction['type_cycle']) ? $dataTransaction['type_cycle'] : 0,
        ]);
        if (isset($ret['id'])) {
            $ret['id'] = $dataTransaction['id'];
        }
        if (empty($ret['total_value'])) {
            unset($ret['total_value']);
        }
        if (empty($ret['net_value'])) {
            unset($ret['net_value']);
        }

        return $ret;
    }

    private function mapDataDetail(array $data = [], $transactionId)
    {
        $dueDate        = null;
        $paymentDate    = null;
        $competenceDate = null;
        $netValue       = null;
        if (isset($data['due_date'])) {
            $dueDate = Carbon::createFromFormat('d/m/Y', $data['due_date']);
            $dueDate = $dueDate->format('Y-m-d');
        }
        if (isset($data['payment_date']) && !empty($data['payment_date'])) {
            $paymentDate = Carbon::createFromFormat('d/m/Y', $data['payment_date']);
            $paymentDate = $paymentDate->format('Y-m-d');
        }
        if (isset($data['competence_date']) && !empty($data['competence_date'])) {
            $competenceDate = Carbon::createFromFormat('d/m/Y', $data['competence_date']);
            $competenceDate = $competenceDate->format('Y-m-d');
        }
        if (isset($data['net_value'])) {
            $netValue = FloatConversor::convert($data['net_value']);
            if (isset($data['main_parent_category_id']) && ($data['main_parent_category_id'] == BankCategory::CATEGORY_TO_PAY)) {
                $netValue *= -1;
            }
        }

        $ret = array_merge($data, [
            'bank_invoice_transaction_id' => $transactionId,
            'due_date'                    => $dueDate,
            'competence_date'             => $competenceDate,
            'payment_date'                => $paymentDate,
            'net_value'                   => $netValue,
            'gross_value'                 => $netValue,
        ]);

        if (empty($dueDate)) {
            unset($ret['due_date']);
        }
        if (empty($netValue)) {
            unset($ret['net_value']);
        }

        return $ret;
    }

    public function store(array $input, $rules = false)
    {
        \DB::beginTransaction();
        try {
            $details      = [];
            $mainCategory = $this->bankCategoryService->findBy('id', $input['BankInvoiceDetail']['bank_category_id']);

            $input['BankInvoiceDetail']['main_parent_category_id'] = $mainCategory->main_parent_category_id;

            $dataTransaction   = $this->mapData($input);
            $recordTransaction = parent::store($dataTransaction, $rules);

            $dataDetail   = $this->mapDataDetail($input['BankInvoiceDetail'], $recordTransaction['id']);
            $firstDueDate = $dataDetail['due_date'];
            for ($parcelNumber = 1; $parcelNumber <= (int) $dataTransaction['total_invoices']; $parcelNumber++) {
                if ($parcelNumber > 1) {
                    $dueDate                = $this->setNewDueDateTypeCycle($dataTransaction['type_cycle'], $firstDueDate, ($parcelNumber - 1));
                    $dataDetail['due_date'] = $dueDate->format('Y-m-d');
                }
                $invoice = $this->addInvoice($dataDetail, $parcelNumber);
                if (method_exists($invoice, 'getErrors') && $invoice->getErrors()) {
                    throw new \Exception("Erro na tentativa de incluir a parcela.", 500);
                }
                $details[] = $invoice;
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

    public function updateInvoices(Model $model, array $input, $updateOption = self::OPTION_ONLY_THIS)
    {

        \DB::beginTransaction();
        try {
            $mainCategory = $this->bankCategoryService->findBy('id', $input['BankInvoiceDetail']['bank_category_id']);

            $input['BankInvoiceDetail']['main_parent_category_id'] = $mainCategory->main_parent_category_id;

            $dataTransaction = $this->mapData($input);
            unset($dataTransaction['total_invoices']);
            unset($dataTransaction['type_cycle']);
            $recordTransaction = parent::store($dataTransaction, 'update');

            $dataDetail   = $this->mapDataDetail($input['BankInvoiceDetail'], $recordTransaction->id);
            $recordDetail = $this->bankInvoiceDetailService->store($dataDetail, 'update');

            switch ($updateOption) {
                case self::OPTION_THIS_FUTURE:
                    $dataDetailFuture = $dataDetail;
                    unset($dataDetailFuture['id']);
                    unset($dataDetailFuture['due_date']);
                    unset($dataDetailFuture['status']);
                    unset($dataDetailFuture['main_parent_category_id']);

                    $this->bankInvoiceDetailService
                        ->repository
                        ->where('bank_invoice_transaction_id', '=', $recordDetail->bank_invoice_transaction_id)
                        ->where('parcel_number', '>', $recordDetail->parcel_number)
                        ->update($dataDetailFuture);
                    break;
                case self::OPTION_ALL:
                    $dataDetailAll = $dataDetail;
                    unset($dataDetailAll['id']);
                    unset($dataDetailAll['due_date']);
                    unset($dataDetailAll['status']);
                    unset($dataDetailAll['bank_account_id']);
                    unset($dataDetailAll['main_parent_category_id']);

                    $this->bankInvoiceDetailService
                        ->repository
                        ->where('bank_invoice_transaction_id', $recordDetail->bank_invoice_transaction_id)
                        ->update($dataDetailAll);
                    break;
            }

            \DB::commit();
            return $recordDetail;

        } catch (\PDOException $e) {
            dd($e);
            \DB::rollBack();
            abort(500, 'Erro na tentativa de criar o lançamento.');
        }
        return null;
    }

    public function destroyInvoices($id, $deleteOption = self::OPTION_ALL)
    {
        $recordDetail = $this->findByInvoice($id);
        \DB::beginTransaction();
        try {
            switch ($deleteOption) {
                case self::OPTION_ONLY_THIS:
                    $recordDetail->delete();
                    break;
                case self::OPTION_ALL_PENDENT:
                    $this->bankInvoiceDetailService
                        ->repository
                        ->where('bank_invoice_transaction_id', '=', $recordDetail->bank_invoice_transaction_id)
                        ->where('status', '<>', BankInvoiceDetail::STATUS_PAID)
                        ->delete();
                    break;
                case self::OPTION_ALL:
                    $recordDetail->delete();

                    $this->bankInvoiceDetailService
                        ->repository
                        ->where('bank_invoice_transaction_id', $recordDetail->bank_invoice_transaction_id)
                        ->delete();
                    break;
            }
            \DB::commit();
            return true;
        } catch (\PDOException $e) {
            \DB::rollBack();
            abort(500, 'Erro na tentativa de excluir o lançamento.');
        }
        return null;
    }

    public function findByInvoice($id)
    {
        return $this->bankInvoiceRepository->find($id);
    }

    public function getAll(array $search = [])
    {
        return $this->repository->getAll($search);
    }

}
