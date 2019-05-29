<?php

namespace Sisnanceiro\Models;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;


class StoreProductCategory extends Model
{
    use TenantModels;

    protected $table      = 'store_product_category';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name',
    ];
}
