<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditCardBrand extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'credit_card_brand';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
    ];

}
