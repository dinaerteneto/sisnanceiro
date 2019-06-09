<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table      = 'customer';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
    ];

}
