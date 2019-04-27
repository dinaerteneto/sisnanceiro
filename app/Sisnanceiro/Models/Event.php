<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use TenantModels;

    protected $table      = 'event';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name',
        'start_date',
        'end_date',
        'people_limit',
        'guest_limit_per_person',
        'value_per_person',
        'description',
        'zipcode',
        'address',
        'address_number',
        'city',
        'district',
        'complement',
        'reference',
        'latitude',
        'longitude'
    ];

    protected $hidden = [
        'company_id',
    ];


}
