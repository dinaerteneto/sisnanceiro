<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use TenantModels;

    protected $table = 'setting';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_id', 'simple_product',
    ];

    public function company()
    {
        return $this->hasOne('Sisnanceiro\Models\Company', 'company_id', 'company_id');
    }

}
