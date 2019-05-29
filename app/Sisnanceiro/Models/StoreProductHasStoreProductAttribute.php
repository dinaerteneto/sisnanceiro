<?php

namespace Sisnanceiro\Models;
use Illuminate\Database\Eloquent\Model;

class StoreProductHasStoreProductAttribute extends Model
{
    use TenantModels;

    protected $table      = 'store_product_has_store_product_attribute';
    protected $primaryKey = ['store_product_id', 'store_product_attribute_id'];

    protected $fillable = [
        'store_product_id',
        'store_product_attribute_id',
        'value',
    ];
}
