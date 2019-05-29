<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProductBrand extends Model
{
    use TenantModels;

    protected $table      = 'store_product_brand';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name',
    ];
}
