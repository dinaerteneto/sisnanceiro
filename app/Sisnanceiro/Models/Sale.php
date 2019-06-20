<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use TenantModels;
    use SoftDeletes;

    const STATUS_ACTIVE = 1;

    protected $table      = 'sale';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'customer_id',
        'user_id_created',
        'user_id_deleted',
        'payment_method_id_fine_value',
        'company_sale_code',
        'status',
        'gross_value',
        'discount_value',
        'net_value',
        'fine_cancel_reason',
        'fine_cancel_value',
        'sale_date',
        'cancel_date',
        'created_at'
    ];

    public function company()
    {
        return $this->hasOne('Sisnanceiro\Models\Company', 'id', 'company_id');
    }

    public function userCreated()
    {
        return $this->hasOne('Sisnanceiro\Models\User', 'id', 'user_id_created');
    }

    public function items()
    {
        return $this->hasMany('Sisnanceiro\Models\SaleItem');
    }

}
