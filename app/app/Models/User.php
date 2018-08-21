<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

}
