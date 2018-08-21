<?php

namespace App\Models;

// use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
    // use TenantModels;


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
