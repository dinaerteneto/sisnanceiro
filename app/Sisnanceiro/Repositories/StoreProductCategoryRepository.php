<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\StoreProductCategory;

class StoreProductCategoryRepository extends Repository
{
    public function __construct(StoreProductCategory $model)
    {
        $this->model = $model;
    }

}
