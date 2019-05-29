<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;

class StoreProductAttribute extends Model
{
    use TenantModels;

    protected $table      = 'store_product_attribute';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name',
    ];
}
