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

    public static function get($payment_method)
    {
        switch ($payment_method) {
            case self::DEBIT_CARD:
                return 'Cartão de débito';
                break;
            case self::CREDIT_CARD:
                return 'Cartão de crédito';
                break;
            case self::MONEY:
                return 'Dinheiro';
                break;
            case self::BANK_DRAFT:
                return 'Cheque';
                break;
            case self::ORDER:
                return 'Boleto';
            case self::TRANSFER:
                return 'Transferência';
                break;
            case self::ONLINE_CARD:
                return 'Crédito online';
                break;
            case self::ONLINE_ORDER:
                return 'Boleto online';
                break;
            case self::DEBIT_AUTOM:
                return 'Débito Automático';
                break;
            case self::DEPOSIT:
                return 'Depósito';
                break;
        }
    }
}
