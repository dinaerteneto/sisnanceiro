<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\StoreProduct;

class StoreProductRepository extends Repository
{
    public function __construct(StoreProduct $model)
    {
        $this->model = $model;
    }

}
