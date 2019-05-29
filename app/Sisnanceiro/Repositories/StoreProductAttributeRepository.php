<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\StoreProductAttribute;

class StoreProductAttributeRepository extends Repository
{
    public function __construct(StoreProductAttribute $model)
    {
        $this->model = $model;
    }

}
