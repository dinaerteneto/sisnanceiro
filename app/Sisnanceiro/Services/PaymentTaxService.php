<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\PaymentMethod;
use Sisnanceiro\Models\PaymentTax;
use Sisnanceiro\Repositories\PaymentTaxRepository;

class PaymentTaxService extends Service
{
    protected $rules = [
        'create' => [
            'bank_account_id'   => 'required|int',
            'payment_method_id' => 'required|int',
            'days_for_payment'  => 'required|int',
        ],
        'update' => [
            'id'                => 'required|int',
            'bank_account_id'   => 'required|int',
            'payment_method_id' => 'required|int',
            'days_for_payment'  => 'required|int',
        ],
    ];

    public function __construct(
        Validator $validator,
        PaymentTaxRepository $repository,
        PaymentTaxTermService $taxTermService
    ) {
        $this->validator      = $validator;
        $this->repository     = $repository;
        $this->taxTermService = $taxTermService;
    }

    private function mapData(array $data)
    {
        return [
        ];
    }

    private function addTerm(PaymentTax $model, array $data = [])
    {
        $taxTerm = false;
        $this->taxTermService->deleteBy('payment_tax_id', $model->id);
        if (PaymentMethod::CREDIT_CARD == $model->payment_method_id) {
            // persist tax of the credit card
            if ($data) {
                foreach ($data['value'] as $key => $value) {
                    $dataTaxTerm = [
                        'payment_tax_id' => $model->id,
                        'order'          => $data['order'][$key],
                        'percent'        => true,
                        'value'          => FloatConversor::convert($value),
                    ];
                    $taxTerm = $taxTerm = $this->taxTermService->store($dataTaxTerm, 'create');
                    if (!$taxTerm) {
                        throw new \Exception('Erro na tentativa de incluir/alterar a taxa do cartão de crédito.');
                    }
                }
            }
        } else {
            $rule        = 'create';
            $dataTaxTerm = [
                'payment_tax_id'    => $model->id,
                'payment_method_id' => $model->payment_method_id,
                'value'             => FloatConversor::convert($data['value']),
                'order'             => 1,
                'percent'           => $data['percent'],
            ];
            if (!empty($postPaymentTaxTerm['id'])) {
                $rule              = 'update';
                $dataTaxTerm['id'] = $postPaymentTaxTerm['id'];
            }
            $taxTerm = $this->taxTermService->store($dataTaxTerm, $rule);
            if (!$taxTerm) {
                throw new \Exception('Erro na tentativa de incluir/alterar a taxa.');
            }

        }
        return $taxTerm;
    }

    public function store(array $data, $rules = false)
    {
        \DB::beginTransaction();
        try {
            $model = parent::store($data['PaymentTax'], $rules);
            if (isset($data['CreditCard'])) {
                $this->addTerm($model, $data['CreditCard']);
            } else {
                $this->addTerm($model, $data['PaymentTaxTerm']);
            }
            \DB::commit();
            return $model;
        } catch (\PDOException $e) {
            \DB::rollBack();
            abort(500, 'Erro na tentativa.');
        }
    }

    public function getAll($search = null)
    {
        return $this->paymentTaxRepository->getAll($search);
    }

    public function findBy($column, $value)
    {
        $item = $this->repository->findBy($column, $value);
        if ($item) {
            $item->with('bank_account');
            return $item;
        } else {
            $this->validator->addError('not_found', 'id', 'No record found for this id.');
            return false;
        }
    }
}
