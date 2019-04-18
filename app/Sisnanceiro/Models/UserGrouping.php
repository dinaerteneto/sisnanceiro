<?php

namespace Sisnanceiro\Models;

use Illuminate\Database\Eloquent\Model;

class UserGrouping extends Model
{

    protected $table      = 'user_grouping';
    protected $primaryKey = null;

    protected $fillable = [
        'user_id', 'user_group_id'
    ];

}
