<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankInvoiceDetail extends Model
{
    use TenantModels;
    use SoftDeletes;

    const STATUS_ACTIVE          = 1;
    const STATUS_CANCELLED       = 2;
    const STATUS_PAID            = 3;
    const STATUS_SEND_GATEWAY    = 4;
    const STATUS_AUTHORIZED      = 5;
    const STATUS_REFUSED         = 6;
    const STATUS_WAITING_PAYMENT = 7;
    const STATUS_REFUNDED        = 8;
    const STATUS_PENDING_REFUND  = 9;
    const STATUS_NOT_CARD        = 10;
    const STATUS_CHARGE_BACK     = 11;

    protected $table      = 'bank_invoice_detail';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'parent_id',
        'customer_id',
        'supplier_id',
        'bank_category_id',
        'bank_account_id',
        'credit_card_id',
        'bank_invoice_transaction_id',
        'payment_method_id',
        'payment_tax_term_id',
        'user_id',
        'gross_value',
        'discount_value',
        'tax_value',
        'rate_value',
        'net_value',
        'payment_value',
        'parcel_number',
        'competence_date',
        'due_date',
        'due_day',
        'payment_date',
        'receive_date',
        'receive_date_availabre',
        'note',
        'status',
        'status_reason',
        'payment_tax_percent',
        'gateway_transaction',
        'grouped',
        'authorization_code',
        'hide',
    ];

    public function company()
    {
        return $this->hasOne('Sisnanceiro\Models\Company', 'id', 'company_id');
    }

    public function category()
    {
        return $this->hasOne('Sisnanceiro\Models\BankCategory', 'id', 'bank_category_id');
    }

    public function account()
    {
        return $this->hasOne('Sisnanceiro\Models\BankAccount', 'id', 'bank_account_id');
    }

    public function transaction()
    {
        return $this->hasOne('Sisnanceiro\Models\BankInvoiceTransaction', 'id', 'bank_invoice_transaction_id');
    }

    public function supplier()
    {
        return $this->hasOne('Sisnanceiro\Models\Supplier', 'id', 'supplier_id');
    }

    public function customer()
    {
        return $this->hasOne('Sisnanceiro\Models\Customer', 'id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne('Sisnanceiro\Models\User', 'id', 'user_id');
    }
}
