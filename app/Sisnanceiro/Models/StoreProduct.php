<?php

namespace Sisnanceiro\Models;

class StoreProduct extends Models
{
    use TenantModels;

    protected $table      = 'store_product';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'store_product_brand_id',
        'store_product_category_id',
        'store_product_id',
        'bank_category_id',
        'name',
        'sku',
        'price',
        'weight',
        'description',
        'total_in_stock',
        'sale_with_negative_stock',
        'status'
    ];
}
