<?php

namespace Sisnanceiro\Models;

class StoreProductImage extends Models
{
    use TenantModels;

    protected $table      = 'store_product_image';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'store_product_id',
        'default',
        'name',
        'url',
        'json_attributes',
    ];
}
