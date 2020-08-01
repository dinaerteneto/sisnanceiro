<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTaxTerm extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'payment_tax_term';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'company_id',
        'payment_tax_id',
        'order',
        'percent',
        'value',
    ];

    public function paymentTax()
    {
        return $this->hasOne('Sisnanceiro\Models\PaymentTax', 'id');
    }

}
