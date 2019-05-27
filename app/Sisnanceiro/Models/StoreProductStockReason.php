<?php

namespace Sisnanceiro\Models;

class StoreProductStockReason extends Models
{
    use TenantModels;

    protected $table      = 'store_product_stock_reason';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'reason',
        'type',
        'sort'
    ];
}
