<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Repositories\CreditCardRepository;
use Sisnanceiro\Services\BankTransactionService;

class CreditCardService extends Service
{

    protected $rules = [
        'create' => [
            'bank_account_id'      => 'required|int',
            'credit_card_brand_id' => 'required|int',
            'name'                 => 'required|string',
            'limit'                => 'required|numeric',
            'payment_day'          => 'required|int',
            'closing_day'          => 'required|int',
        ],
        'update' => [
            'bank_account_id'      => 'required|int',
            'credit_card_brand_id' => 'required|int',
            'name'                 => 'required|string',
            'limit'                => 'required|numeric',
            'payment_day'          => 'required|int',
            'closing_day'          => 'required|int',
        ],
    ];

    protected $repository;

    public function __construct(
        Validator $validator,
        CreditCardRepository $creditCardRepository,
        BankTransactionService $bankTransactionService
    ) {
        $this->validator              = $validator;
        $this->repository             = $creditCardRepository;
        $this->bankTransactionService = $bankTransactionService;
    }

    public function closeInvoice($date)
    {
        $query = \DB::table('bank_invoice_detail')
            ->selectRaw('
                SUM(bank_invoice_detail.net_value) AS net_value
              , bank_invoice_detail.credit_card_id
              , bank_invoice_detail.bank_account_id
              , credit_card.name'
            )
            ->join('credit_card', 'credit_card.id', '=', 'bank_invoice_detail.credit_card_id')
            ->leftJoin('bank_invoice_transaction', function ($join) use ($date) {
                $join->on('bank_invoice_transaction.credit_card_id', '=', 'bank_invoice_detail.credit_card_id')
                    ->where('bank_invoice_transaction.credit_card_due_date', '=', $date);
            })
            ->where('due_date', $date)
            ->whereNotNull('bank_invoice_detail.credit_card_id')
            ->whereNull('bank_invoice_transaction.id')
            ->whereNull('bank_invoice_detail.deleted_at')
            ->whereNull('credit_card.deleted_at')
            ->groupBy('bank_invoice_detail.credit_card_id')
            ->get();

        if ($query) {
            $dateCarbon = Carbon::createFromFormat('Y-m-d', $date);
            foreach ($query as $invoice) {
                $aData = [
                    'BankInvoiceTransaction' => [
                        'credit_card_id'       => $invoice->credit_card_id,
                        'note'                 => "Fatura do cartão de crédito",
                        'description'          => "Fatura do cartão de crédito",
                        'credit_card_due_date' => $date,
                    ],
                    'BankInvoiceDetail'      => [
                        'bank_category_id' => BankCategory::CATEGORY_TO_PAY,
                        'bank_account_id'  => $invoice->bank_account_id,
                        'net_value'        => $invoice->net_value,
                        'parcel_number'    => 1,
                        'due_date'         => $dateCarbon->format('d/m/Y'),
                    ],
                ];
                $transaction = $this->bankTransactionService->store($aData, 'create');
                if (method_exists($transaction, 'getErrors') && $transaction->getErrors()) {
                    throw new \Exception("Erro na tentativa de incluir a fatura.", 500);
                }

                $sql = "
                    INSERT INTO bank_invoice_detail_has_credit_card_invoice (
                         bank_invoice_detail_id
                       , credit_card_invoice_id
                    )
                    SELECT {$transaction[0]->id}
                         , BID.id
                     FROM bank_invoice_detail BID
                     JOIN credit_card CC
                       ON BID.credit_card_id = CC.id
                    WHERE BID.deleted_at IS NULL
                      AND BID.credit_card_id IS NOT NULL
                      AND BID.due_date = '{$date}'
                      AND CC.deleted_at IS NULL
                ";
                \DB::statement($sql);
            }
        }
        return true;
    }
}
