<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTax extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'payment_tax';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'company_id',
        'bank_account_id',
        'payment_method_id',
        'name',
        'days_for_payment',
        'days_business',
    ];

    public function bankAccount()
    {
        return $this->hasOne('Sisnanceiro\Models\BankAccount', 'id', 'bank_account_id');
    }

    public function paymentTaxTerm()
    {
        return $this->hasMany('Sisnanceiro\Models\PaymentTaxTerm', 'id');
    }

}
