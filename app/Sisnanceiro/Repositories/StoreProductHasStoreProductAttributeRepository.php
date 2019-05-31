<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\StoreProductHasStoreProductAttribute;

class StoreProductHasStoreProductAttributeRepository extends Repository
{
    public function __construct(StoreProductHasStoreProductAttribute $model)
    {
        $this->model = $model;
    }

}
