<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use TenantModels;

    const DEBIT_CARD   = 1;
    const CREDIT_CARD  = 2;
    const MONEY        = 3;
    const BANK_DRAFT   = 4;
    const ORDER        = 5;
    const TRANSFER     = 6;
    const ONLINE_CARD  = 7;
    const ONLINE_ORDER = 8;
    const DEBIT_AUTOM  = 9;
    const DEPOSIT      = 10;

    protected $table      = 'payment_method';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
    ];
}
