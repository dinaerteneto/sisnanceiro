<?php

namespace Sisnanceiro\Services;

use Sisnaceiro\Helpers\FloatConversor;
use Sisnaceiro\Models\PaymentTaxTerm;
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
            'days_business'     => 'boolean',
        ],
        'update' => [
            'bank_account_id'   => 'required|int',
            'payment_method_id' => 'required|int',
            'days_for_payment'  => 'required|int',
            'days_business'     => 'boolean',
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

    private function addTerm(PaymentTax $model, array $data = [])
    {
        $taxTerm = false;
        if (PaymentMethod::CREDIT_CARD == $model->payment_method_id) {
            PaymentTaxTerm::where('payment_tax_id', $model->id)->delete();
            // persist tax of the credit card
            $postCreditCard = $data['CreditCard'];
            if ($postCreditCard) {
                foreach ($postCreditCard['value'] as $key => $value) {
                    $dataTaxTerm = [
                        'payment_tax_id' => $model->id,
                        'order'          => $postCreditCard['order'][$key],
                        'percent'        => true,
                        'value'          => FloatConversor::convert($value),
                    ];
                    $taxTerm = $taxTerm = $this->taxTermService->store('create', $dataTaxTerm);
                    if (!$taxTerm) {
                        throw new \Exception('Erro na tentativa de incluir a taxa do cartão de crédito.');
                    }
                }
            }
        } else {
            // persist tax different credit card
            if (in_array($model->payment_method_id, [PaymentMethod::TRANSFER, PaymentMethod::BANK_DRAFT])) {
                throw new \Exception('Esta forma de pagto não possuí parcelamento.');
            }
            $postPaymentTaxTerm = $data['PaymentTaxTerm'];
            $taxTerm            = $this->taxTermService;
            if (!empty($postPaymentTaxTerm['id'])) {
                $taxTerm = $this->taxTermService($postPaymentTaxTerm['id']);
            }
            $dataTaxTerm['payment_tax_id'] = $model->id;
            $dataTaxTerm['value']          = FloatConversor::convert($taxTerm->value);

            if (!$taxTerm->store($dataTaxTerm)) {
                throw new \Exception('Erro na tentativa de incluir a taxa.');
            }

        }
        return $taxTerm;
    }

    public function store(array $data, $rules = false)
    {
        $model = parent::store($data, $rules);
        if (isset($data['PaymentTaxTerm'])) {
            $this->addTerm($model, $data);
        }
        return $model;
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
