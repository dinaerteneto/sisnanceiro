<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class PersonContactType extends Model
{

    use TenantModels;

    protected $table      = 'person_contact_type';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id', 
        'type'
    ];

    protected $hidden = [

    ];

}
