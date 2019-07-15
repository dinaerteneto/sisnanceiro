<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use TenantModels;

    protected $table      = 'bank';
    protected $primaryKey = 'id';
}
