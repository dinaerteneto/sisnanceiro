<?php

namespace Sisnanceiro\Models;

class StoreProductCategory extends Models
{
    use TenantModels;

    protected $table      = 'store_product_category';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name',
    ];
}
