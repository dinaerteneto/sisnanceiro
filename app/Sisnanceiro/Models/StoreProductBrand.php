<?php

namespace Sisnanceiro\Models;

class StoreProductBrand extends Models
{
    use TenantModels;

    protected $table      = 'store_product_brand';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name',
    ];
}
