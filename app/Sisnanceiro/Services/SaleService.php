<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\Sale;
use Sisnanceiro\Repositories\SaleItemRepository;
use Sisnanceiro\Repositories\SaleRepository;

class SaleService extends Service
{
    protected $rules = [
        'create' => [
            'customer_id'                  => 'required|int',
            'user_id_created'              => 'required|int',
            'user_id_deleted'              => 'required|int',
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
            'customer_id'       => 'required|int',
            'user_id_created'   => 'required|int',
            // 'user_id_deleted'              => 'required|int',
            // 'payment_method_id_fine_value' => 'required|int',
            'company_sale_code' => 'required|int',
            'status'            => 'int',
            'gross_value'       => 'required|numeric',
            'discount_value'    => 'required|numeric',
            'net_value'         => 'required|numeric',
            // 'fine_cancel_reason'           => 'string',
            // 'fine_cancel_value'            => 'numeric',
            'sale_date'         => 'string',
            // 'cancel_date'                  => 'string',
        ],
    ];

    public function __construct(Validator $validator, SaleRepository $repository, SaleItemRepository $repositorySaleItem)
    {
        $this->validator          = $validator;
        $this->repository         = $repository;
        $this->repositorySaleItem = $repositorySaleItem;
    }

    private function mapData(array $data)
    {
        $userId     = Auth::user()->id;
        $customerId = isset($data['customer_id']) && !empty($data['customer_id']) ? $data['customer_id'] : null;
        $saleCode   = $this->getLastSaleCode() + 1;

        return [
            'customer_id'       => $customerId,
            'user_id_created'   => $userId,
            'company_sale_code' => $saleCode,
            'status'            => Sale::STATUS_ACTIVE,
            'gross_value'       => FloatConversor::convert($data['net_value']),
            'net_value'         => FloatConversor::convert($data['net_value']),
            'sale_date'         => Carbon::now()->format('Y-m-d'),
        ];
    }

    private function mapItem(Sale $sale, array $data)
    {
        return [
            'sale_id'          => $sale->id,
            'store_product_id' => $data['store_product_id'],
            'discount_type'    => $data['discount_type'],
            'unit_value'       => FloatConversor::convert($data['unit_value']),
            'discount_value'   => FloatConversor::convert($data['discount_value']),
            'quantity'         => FloatConversor::convert($data['quantity']),
            'total_value'      => FloatConversor::convert($data['total_value']),
        ];
    }

    /**
     * get last sale code
     * @return int
     */
    private function getLastSaleCode()
    {
        $model = $this->repository->orderBy('created_at', 'desc')->first();
        if (!$model) {
            return 0;
        }
        return $model->id;
    }

    public function create(array $data)
    {
        $saleData  = $this->mapData($data['Sale']);
        $saleModel = $this->store($saleData);
        if (isset($data['SaleItem'])) {
            foreach ($data['SaleItem'] as $item) {
                $itemData = $this->mapItem($saleModel, $item);
                $this->repositorySaleItem->create($itemData);
            }
        }
        return $saleModel;
    }

    public function getAll($search = null)
    {
        return $this->repository->getAll($search);
    }

    public function find($id, $with = false)
    {
        $with = [
            'company', 
            'userCreated', 
            'customer', 
            'items',
        ];
        return parent::find($id, $with)
            ->load('company', 'userCreated', 'customer', 'items');
    }

}
