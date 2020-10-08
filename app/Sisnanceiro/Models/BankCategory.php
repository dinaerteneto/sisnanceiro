<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class BankCategory extends Model
{
 use TenantModels;

 const STATUS_INACTIVE = 0;
 const STATUS_ACTIVE   = 1;

 const CATEGORY_INITIAL_BALANCE     = 1;
 const CATEGORY_TO_PAY              = 2;
 const CATEGORY_TO_RECEIVE          = 3;
 const CATEGORY_SALE                = 4;
 const CATEGORY_TRANSFER            = 5;
 const CATEGORY_TRANSFER_OUT        = 6;
 const CATEGORY_TRANSFER_IN         = 7;
 const CATEGORY_CREDIT_INVOICE      = 8;
 const CATEGORY_CREDIT_CARD_BALANCE = 9;

 protected $table      = 'bank_category';
 protected $primaryKey = 'id';

 protected $fillable = [
  'company_id',
  'main_parent_category_id',
  'parent_category_id', 'name',
  'status',
  'can_delete',
 ];

 public function invoices()
 {
  return $this->hasMany('Sisnanceiro\Models\BankInvoiceDetail', 'bank_category_id');
 }

}
