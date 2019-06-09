<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class PersonAddress extends Model
{

    use TenantModels;

    protected $table      = 'person_address';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'person_id',
        'person_address_type_id',
        'name',
        'zip_code',
        'address',
        'number',
        'complement',
        'reference',
        'city',
        'district',
        'uf',
        'latitude',
        'longitude',
    ];

    protected $hidden = [

    ];

}
