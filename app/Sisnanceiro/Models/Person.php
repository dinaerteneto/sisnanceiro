<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    use TenantModels;

    protected $table      = 'person';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id', 'firstname', 'lastname', 'physical', 'gender', 'email'
    ];

    protected $hidden = [
        
    ];

}
