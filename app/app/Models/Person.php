<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;

class Person extends Model
{

    use TenantModels;

    protected $table = 'people';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id', 'name', 'last_name', 'physical', 'gender'
    ];

}
