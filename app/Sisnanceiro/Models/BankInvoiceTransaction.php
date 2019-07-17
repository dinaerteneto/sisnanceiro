<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class BankInvoiceTransaction extends Model
{
    use TenantModels;

    const TYPE_CYCLE_DIALY   = 0;
    const TYPE_CYCLE_WEEKLY  = 1;
    const TYPE_CYCLE_MONTHLY = 2;
    const TYPE_CYCLE_YEARLY  = 4;

    protected $table      = 'bank_invoice_transaction';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'bank_category_id',
        'total_invoices',
        'total_value',
        'type_cicle',
        'description',
    ];

    public static function getTypeCycle($type)
    {
        $ret = null;
        switch ($type) {
            case self::TYPE_CYCLE_DIALY:
                $ret = 'Dias';
                break;

            case self::TYPE_CYCLE_WEEKLY:
                $ret = 'Semanas';
                break;

            case self::TYPE_CYCLE_MONTHLY:
                $ret = 'Meses';
                break;

            case self::TYPE_CYCLE_YEARLY:
                $ret = 'Anual';
                break;

        }
        return $ret;
    }

    public static function getTypeCicles()
    {
        return [
            0 => 'Dias',
            1 => 'Semanas',
            2 => 'Meses',
            3 => 'Anos',
        ];
    }

}
