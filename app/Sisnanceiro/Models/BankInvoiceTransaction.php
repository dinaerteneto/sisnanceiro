<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;

class BankInvoiceTransaction extends Model
{
    use TenantModels;

    const TYPE_CYCLE_DIALY     = 0;
    const TYPE_CYCLE_WEEKLY    = 1;
    const TYPE_CYCLE_MONTHLY   = 2;
    const TYPE_CYCLE_BIMONTHLY = 3;
    const TYPE_CYCLE_QUARTERLY = 4; //trimestral
    const TYPE_CYCLE_SEMESTER  = 5;
    const TYPE_CYCLE_YEARLY    = 6;

    protected $table = 'bank_invoice_transaction';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id', 'bank_category_id', 'total_invoices', 'total_value', 'type_cicle', 'description'
    ];
}
