<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\Sale;
use Sisnanceiro\Repositories\SaleItemRepository;
use Sisnanceiro\Repositories\SaleRepository;
use Sisnanceiro\Services\CartService;

class SaleService extends Service
{
    protected $rules = [
        'create' => [
            'customer_id' => 'required|int',
            'user_id_created' => 'required|int',
            'user_id_deleted' => 'required|int',
            'payment_method_id_fine_value' => 'required|int',
            'company_sale_code' => 'required|int',
            'status' => 'int',
            'gross_value' => 'required|numeric',
            'discount_value' => 'required|numeric',
            'net_value' => 'required|numeric',
            'fine_cancel_reason' => 'string',
            'fine_cancel_value' => 'numeric',
            'sale_date' => 'string',
            'cancel_date' => 'string',
        ],
        'update' => [
            'customer_id' => 'required|int',
            'user_id_created' => 'required|int',
            // 'user_id_deleted'              => 'required|int',
            // 'payment_method_id_fine_value' => 'required|int',
            'company_sale_code' => 'required|int',
            'status' => 'int',
            'gross_value' => 'required|numeric',
            // 'discount_value'    => 'required|numeric',
            'net_value' => 'required|numeric',
            // 'fine_cancel_reason'           => 'string',
            // 'fine_cancel_value'            => 'numeric',
            'sale_date' => 'string',
            // 'cancel_date'                  => 'string',
        ],
    ];

    public function __construct(Validator $validator, SaleRepository $repository, SaleItemRepository $repositorySaleItem, CartService $cartService)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->repositorySaleItem = $repositorySaleItem;
        $this->cartService = $cartService;
    }

    private function mapData(array $data)
    {
        $userId = Auth::user()->id;
        $customerId = isset($data['customer_id']) && !empty($data['customer_id']) ? $data['customer_id'] : null;
        $saleCode = $this->getLastSaleCode() + 1;

        return [
            'customer_id' => $customerId,
            'user_id_created' => $userId,
            'company_sale_code' => $saleCode,
            'status' => Sale::STATUS_ACTIVE,
            'gross_value' => FloatConversor::convert($data['gross_value']),
            'net_value' => FloatConversor::convert($data['net_value']),
            'sale_date' => Carbon::now()->format('Y-m-d'),
            'discount_value' => FloatConversor::convert($data['discount_value']),
            'discount_type' => $data['discount_type'],
        ];
    }

    private function mapItem(Sale $sale, array $data)
    {
        return [
            'sale_id' => $sale->id,
            'store_product_id' => $data['store_product_id'],
            'discount_type' => $data['discount_type'],
            'unit_value' => FloatConversor::convert($data['unit_value']),
            'discount_value' => FloatConversor::convert($data['discount_value']),
            'quantity' => FloatConversor::convert($data['quantity']),
            'total_value' => FloatConversor::convert($data['total_value']),
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
        \DB::beginTransaction();
        try {
            $saleData = $this->mapData($data['Sale']);
            $saleModel = $this->store($saleData);
            if (isset($data['SaleItem'])) {
                foreach ($data['SaleItem'] as $item) {
                    $itemData = $this->mapItem($saleModel, $item);
                    $this->repositorySaleItem->create($itemData);
                }
            }
            \DB::commit();

            $this->cartService->deleteByToken($data['Sale']['token']);

            return $saleModel;
        } catch (\PDOException $e) {
            \DB::rollBack();
            $error = ['message' => 'erro na tentativa de criar a venda', 'postData' => $data, 'PDOException' => $e->getMessage()];
            Log::debug(json_encode($error));
            abort(500, 'Erro na tentativa de criar a venda.');
        }
    }

    public function update(Model $model, array $data, $rules = 'update')
    {
        \DB::beginTransaction();
        try {
            $saleData = $this->mapData($data['Sale']);
            $saleModel = $model->update($saleData);

            // remove all items before add
            $model->items()->delete();

            if (isset($data['SaleItem'])) {
                foreach ($data['SaleItem'] as $item) {
                    $itemData = $this->mapItem($model, $item);
                    $this->repositorySaleItem->create($itemData);
                }
            }

            parent::update($model, $data, $rules);
            \DB::commit();

            return $saleModel;
        } catch (\PDOException $e) {
            \DB::rollBack();
            abort(500, 'Erro na tentativa.');
        }
    }

    public function getAll($search = null)
    {
        return $this->repository->getAll($search);
    }

    public function find($id, $with = false)
    {
        return $this->repository->find($id);
    }

}
