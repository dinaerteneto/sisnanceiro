<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class SaleTemp extends Model
{
    use TenantModels;

    protected $table = 'sale_temp';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'customer_id',
        'token',
        'gross_value',
        'discount_value',
        'net_value',
    ];

    public function company()
    {
        return $this->hasOne('Sisnanceiro\Models\Company', 'id', 'company_id');
    }


    public function customer()
    {
        return $this->hasOne('Sisnanceiro\Models\Customer', 'id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany('Sisnanceiro\Models\SaleItemTemp');
    }

}
