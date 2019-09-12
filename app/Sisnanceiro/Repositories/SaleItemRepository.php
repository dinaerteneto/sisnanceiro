<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Sale;
use Sisnanceiro\Models\SaleItem;

class SaleItemRepository extends Repository
{
    public function __construct(SaleItem $model)
    {
        $this->model = $model;
    }

    public function removeAllBySale(Sale $saleModel) {
        return $saleModel->items()->delete();
    }
}
