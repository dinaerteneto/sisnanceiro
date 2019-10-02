<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class SaleItemTemp extends Model
{
    use TenantModels;

    protected $table = 'sale_item_temp';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'sale_temp_id',
        'store_product_id',
        'quantity',
        'discount_type',
        'unit_value',
        'total_value',
        'discount_value',
    ];

    public function company()
    {
        return $this->hasOne('Sisnanceiro\Models\Company', 'id', 'company_id');
    }

    public function SaleTemp()
    {
        return $this->hasOne('Sisnanceiro\Models\SaleTemp', 'id', 'sale_temp_id');
    }

    public function product()
    {
        return \DB::table('store_product')
            ->where('id', '=', $this->store_product_id);
    }    

}
