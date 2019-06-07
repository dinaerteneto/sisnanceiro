<?php

namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\StoreProductCategory;

class StoreProductCategoryTransform extends TransformerAbstract
{

    /**
     * Transform the product categories for controller return
     * @param StoreProductCategory $storeProductCategory
     * @return array
     */
    public function transform(StoreProductCategory $storeProductCategory)
    {
        return [
            'id'   => $storeProductCategory->id,
            'name' => $storeProductCategory->name,
        ];
    }
}
