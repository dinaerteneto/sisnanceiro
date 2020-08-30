<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class BankInvoiceDetailHasCreditCardInvoice extends Model
{
    use TenantModels;

    protected $table = 'bank_invoice_details_has_credit_card_invoice';

    protected $fillable = [
        'credit_card_id_invoice',
        'bank_invoice_detail_id',
    ];

}
