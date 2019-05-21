<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'password_generated',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the company
     */
    public function company()
    {
        return $this->hasOne('Sisnanceiro\Models\Company', 'id', 'company_id');
    }

    /**
     * Get the person
     */
    public function person()
    {
        return $this->hasOne('Sisnanceiro\Models\Person', 'id', 'id');
    }
}
