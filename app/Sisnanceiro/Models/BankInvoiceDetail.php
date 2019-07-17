<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class BankInvoiceDetail extends Model
{
    use TenantModels;

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
        'bank_invoice_transaction_id',
        'payment_method_id',
        'payment_tax_term_id',
        'gross_value',
        'discount_value',
        'tax_value',
        'rate_value',
        'net_value',
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
}
