<?php

namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\StoreProductAttribute;

class StoreProductAttributeTransformer extends TransformerAbstract
{

    /**
     * Transform the product brands for controller return
     * @param StoreProductAttribute $storeProductBrand
     * @return array
     */
    public function transform(StoreProductAttribute $storeProductAttribute)
    {
        return [
            'id'   => $storeProductAttribute->id,
            'name' => $storeProductAttribute->name,
        ];
    }
}
