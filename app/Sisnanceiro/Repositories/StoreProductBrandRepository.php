<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\StoreProductBrand;

class StoreProductBrandRepository extends Repository
{
    public function __construct(StoreProductBrand $model)
    {
        $this->model = $model;
    }

}
