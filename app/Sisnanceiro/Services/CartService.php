<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Models\SaleItemTemp;
use Sisnanceiro\Models\SaleTemp;
use Illuminate\Support\Facades\Log;

class CartService extends Service
{

    public function __construct(SaleTemp $modelSaleTemp, SaleItemTemp $modelSaleItemTemp)
    {
        $this->modelSaleTemp = $modelSaleTemp;
        $this->modelSaleItemTemp = $modelSaleItemTemp;
    }

    private function mapData(array $data)
    {
        $customerId = isset($data['customer_id']) && !empty($data['customer_id']) ? $data['customer_id'] : null;
        return [
            'token' => $data['token'],
            'customer_id' => $customerId,
            'discount_value' => FloatConversor::convert($data['discount_value']),
            'gross_value' => FloatConversor::convert($data['gross_value']),
            'net_value' => FloatConversor::convert($data['net_value']),
            'sale_date' => Carbon::now()->format('Y-m-d'),
        ];
    }

    private function mapDataItem(SaleTemp $saleTemp, array $data)
    {
        return [
            'sale_temp_id' => $saleTemp->id,
            'store_product_id' => $data['id'],
            'discount_type' => $data['discount_type'],
            'unit_value' => $data['unit_value'],
            'discount_value' => FloatConversor::convert($data['discount_value']),
            'quantity' => FloatConversor::convert($data['quantity']),
            'total_value' => FloatConversor::convert($data['total_value']),
        ];
    }

    public function addItem(array $data)
    {
        \DB::beginTransaction();
        try {
            $modelTempSale = $this->modelSaleTemp
                ->where('token', '=', $data['sale']['token'])
                ->where('company_id', '=', Auth::user()->company_id)
                ->first();

            $saleData = $this->mapData($data['sale']);
            if (!$modelTempSale) {
                $modelTempSale = $this->modelSaleTemp->create($saleData);
            }
            
            $modelTempSale->fill($saleData);
            $modelTempSale->save();

            $saleItemTemp = $this->mapDataItem($modelTempSale, $data['item']);
            $this->modelSaleItemTemp->create($saleItemTemp);
            \DB::commit();
            return true;
        } catch (\PDOException $e) {
            \DB::rollBack();
            $error = ['message' => 'erro na tentativa de criar a venda temporária', 'postData' => $data, 'PDOException' => $e->getMessage()];
            Log::debug(json_encode($error));
            abort(500, 'Erro na tentativa de criar a venda temporária.');
        }
    }

    public function delItem($token, $productId)
    {
        $modelSaleTemp = $this->modelSaleTemp
            ->where('token', '=', $token)
            ->first();

        $modelItemTemp = $this->modelSaleItemTemp
            ->where('sale_temp_id', '=', $modelSaleTemp->id)
            ->where('store_product_id', '=', $productId)
            ->where('company_id', '=', Auth::user()->company_id)
            ->first();

        if ($modelItemTemp) {
            $modelItemTemp->delete();
            return true;
        }
        return false;
    }

}
