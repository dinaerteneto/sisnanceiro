<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankInvoiceTransaction extends Model
{
    use TenantModels;
    use SoftDeletes;

    const TYPE_CYCLE_DIALY   = 1;
    const TYPE_CYCLE_WEEKLY  = 2;
    const TYPE_CYCLE_MONTHLY = 3;
    const TYPE_CYCLE_YEARLY  = 4;

    protected $table      = 'bank_invoice_transaction';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'bank_category_id',
        'bank_account_source_id',
        'bank_account_target_id',
        'credit_card_id',
        'total_invoices',
        'fixed',
        'is_transfer',
        'is_credit_card_invoice',
        'description',
        'note',
        'authorization_code',
        'total_value',
        'type_cycle',
        'description',
        'credit_card_due_date',
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
            1 => 'Dias',
            2 => 'Semanas',
            3 => 'Meses',
            4 => 'Anos',
        ];
    }

    public function accountSource()
    {
        return $this->hasOne('Sisnanceiro\Models\BankAccount', 'id', 'bank_account_source_id');
    }

    public function accountTarget()
    {
        return $this->hasOne('Sisnanceiro\Models\BankAccount', 'id', 'bank_account_target_id');
    }

    public function invoices()
    {
        return $this->hasMany('Sisnanceiro\Models\BankInvoiceDetail', 'bank_invoice_transaction_id');
    }

    public function creditCard()
    {
        return $this->belongsTo('Sisnanceiro\Models\CreditCard', 'credit_card_id');
    }

}
