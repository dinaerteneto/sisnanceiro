<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditCard extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'credit_card';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'company_id',
        'bank_account_id',
        'credit_card_brand_id',
        'name',
        'limit',
        'payment_day',
        'closing_day',
    ];

    public function bankAccount()
    {
        return $this->hasOne('Sisnanceiro\Models\BankAccount', 'id');
    }

    public function CreditCardBrand()
    {
        return $this->hasOne('Sisnanceiro\Models\CreditCardBrand', 'id');
    }

}
