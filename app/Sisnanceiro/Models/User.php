<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use TenantModels;

    const GROUP_MASTER = 1;
    const GROUP_ADMIN  = 2;
    const GROUP_USER   = 3;

    protected $fillable = [
        'id', 'company_id', 'email', 'remember_token', 'password',
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
