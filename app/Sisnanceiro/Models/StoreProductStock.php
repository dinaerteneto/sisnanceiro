<?php

namespace Sisnanceiro\Models;
use Illuminate\Database\Eloquent\Model;
class StoreProductStock extends Model
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
