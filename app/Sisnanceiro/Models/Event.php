<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
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
        'value', 
        'description', 
        'zip_code', 
        'address', 
        'address_number', 
        'city',
        'complement',
        'reference',
        'latitude',
        'longitude'
    ];


}
