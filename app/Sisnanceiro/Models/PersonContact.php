<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class PersonContact extends Model
{

    use TenantModels;

    protected $table      = 'person_contact';
    protected $primaryKey = 'id';

    protected $fillable = [
        // 'company_id', 'name', 'last_name', 'physical', 'gender',
    ];

}
