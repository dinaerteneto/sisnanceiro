<?php

namespace Sisnanceiro\Models;

class StoreProductHasStoreProductAttribute extends Models
{
    use TenantModels;

    protected $table      = 'store_product_has_store_product_attribute';
    protected $primaryKey = ['store_product_id', 'store_product_attribute_id'];

    protected $fillable = [
        'store_product_id',
        'store_product_attribute_id',
        'value',
    ];
}
