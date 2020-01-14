<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'store_product';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'store_product_brand_id',
        'store_product_category_id',
        'store_product_id',
        'bank_category_id',
        'unit_measurement',
        'name',
        'sku',
        'price',
        'cost_price',
        'weight',
        'description',
        'total_in_stock',
        'sale_with_negative_stock',
        'status',
    ];

    public function haveChild()
    {
        return $this->subproducts()->get()->first() ? true : false;
    }

    public function brand()
    {
        return $this->hasOne('Sisnanceiro\Models\StoreProductBrand', 'id', 'store_product_brand_id');
    }

    public function category()
    {
        return $this->hasOne('Sisnanceiro\Models\StoreProductCategory', 'id', 'store_product_category_id');
    }

    public function subproducts()
    {
        return $this->hasMany('Sisnanceiro\Models\StoreProduct', 'store_product_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany('Sisnanceiro\Models\StoreProductHasStoreProductAttribute', 'store_product_id', 'id');
    }
}
