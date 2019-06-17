<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Repositories\SaleRepository;

class SaleService extends Service
{
    protected $rules = [
        'create' => [
            'customer_id'                  => 'required|int',
            'user_id_created_at'           => 'required|int',
            'user_id_deleted_at'           => 'required|int',
            'payment_method_id_fine_value' => 'required|int',
            'company_sale_code'            => 'required|int',
            'status'                       => 'int',
            'gross_value'                  => 'required|numeric',
            'discount_value'               => 'required|numeric',
            'net_value'                    => 'required|numeric',
            'fine_cancel_reason'           => 'string',
            'fine_cancel_value'            => 'numeric',
            'sale_date'                    => 'string',
            'cancel_date'                  => 'string',
        ],
        'update' => [
            'customer_id'                  => 'required|int',
            'user_id_created_at'           => 'required|int',
            'user_id_deleted_at'           => 'required|int',
            'payment_method_id_fine_value' => 'required|int',
            'company_sale_code'            => 'required|int',
            'status'                       => 'int',
            'gross_value'                  => 'required|numeric',
            'discount_value'               => 'required|numeric',
            'net_value'                    => 'required|numeric',
            'fine_cancel_reason'           => 'string',
            'fine_cancel_value'            => 'numeric',
            'sale_date'                    => 'string',
            'cancel_date'                  => 'string',
        ],
    ];

    public function __construct(SaleRepository $repository)
    {
        $this->repository = $repository;
    }

}
