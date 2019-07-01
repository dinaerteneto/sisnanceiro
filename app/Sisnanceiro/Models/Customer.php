<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table      = 'customer';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
    ];

    public function person()
    {
        return $this->hasOne('Sisnanceiro\Models\Person', 'id', 'id');
    }

}
