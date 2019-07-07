<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table      = 'company';
    // protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'url'
    ];

    public function person()
    {
        return $this->hasOne('Sisnanceiro\Models\Person', 'company_id');
    }    

}
