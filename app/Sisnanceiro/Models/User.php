<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use TenantModels;

    protected $fillable = [
        'company_id', 'email', 'remember_token',
    ];

    public function username()
    {
        return 'login';
    }

    public function person()
    {
        return $this->hasOne('Sisnanceiro\Models\Company', 'id', 'company_id');
    }

}
