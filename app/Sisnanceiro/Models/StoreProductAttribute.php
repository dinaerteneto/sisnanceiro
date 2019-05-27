<?php

namespace Sisnanceiro\Models;

class StoreProductAttribute extends Models
{
    use TenantModels;

    protected $table      = 'store_product_attribute';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
