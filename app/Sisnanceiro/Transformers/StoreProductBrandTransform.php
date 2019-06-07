<?php

namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\StoreProductBrand;

class StoreProductBrandTransform extends TransformerAbstract
{

    /**
     * Transform the product brands for controller return
     * @param StoreProductBrand $storeProductBrand
     * @return array
     */
    public function transform(StoreProductBrand $storeProductBrand)
    {
        return [
            'id'   => $storeProductBrand->id,
            'name' => $storeProductBrand->name,
        ];
    }
}
