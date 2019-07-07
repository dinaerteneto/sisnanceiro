<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\SaleItem;

class SaleItemRepository extends Repository
{
    public function __construct(SaleItem $model)
    {
        $this->model = $model;
    }
}
