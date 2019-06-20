<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'sale_item';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'sale_id',
        'store_product_id',
        'type',
        'unit_value',
        'discount_value',
        'discount_reason',
        'discount_type',
        'total_value',
        'quantity',
    ];

    public function product()
    {
        return $this->hasOne('Sisnanceiro\Models\StoreProduct', 'id', 'store_product_id');
    }

}
