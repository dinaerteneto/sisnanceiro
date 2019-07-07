<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonContact extends Model
{

    use TenantModels;
    use SoftDeletes;

    protected $table      = 'person_contact';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'person_id',
        'person_contact_type_id',
        'name',
        'value',
        'description'
    ];

}
