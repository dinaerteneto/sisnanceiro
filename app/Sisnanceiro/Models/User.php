<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;

class User extends Model
{
    use TenantModels;

    protected $fillable = [
        'company_id', 'email', 'remember_token'
    ];

    public function username()
    {
        return 'login';
    }

    public function person()
    {
        return $this->hasOne( 'Sisnanceiro\Models\Company', 'id', 'company_id');
    }

}
