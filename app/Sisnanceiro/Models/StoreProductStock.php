<?php

namespace Sisnanceiro\Models;

class StoreProductStock extends Models
{
    use TenantModels;

    protected $table      = 'store_product_stock';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'store_product_id',
        'sale_id',
        'user_id',
        'store_product_stock_reason_id',
        'total',
        'description'
    ];
}
