<?php

namespace Sisnanceiro\Models;
use Illuminate\Database\Eloquent\Model;

class StoreProductStockReason extends Model
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
