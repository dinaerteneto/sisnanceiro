<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;

class BankCategory extends Model
{
    use TenantModels;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const CATEGORY_OPENING_BALANCE = 1;
    const CATEGORY_TO_PAY = 2;
    const CATEGORY_TO_RECEIVE = 3;
    const CATEGORY_SALE = 4;

    protected $table = 'bank_category';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id', 'main_parent_category_id', 'parent_category_id', 'name', 'status'
    ];

}
