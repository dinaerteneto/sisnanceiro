<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class PersonContactType extends Model
{

    use TenantModels;

    protected $table      = 'person_contact_type';
    protected $primaryKey = 'id';

    const TYPE_EMAIL           = 1;
    const TYPE_CELLPHONE       = 2;
    const TYPE_HOME_PHONE      = 3;
    const TYPE_COMERCIAL_PHONE = 4;
    const TYPE_SCRAP_PHONE     = 5;
    const TYPE_OTHER           = 6;

    protected $fillable = [
        'company_id',
        'type',
    ];

    protected $hidden = [

    ];

}
