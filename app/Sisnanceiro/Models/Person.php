<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'person';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'physical',
        'firstname',
        'lastname',
        'cpf',
        'rg',
        'gender',
        'email',
        'birthdate',
    ];

    public function addresses() {
        return $this->hasMany('Sisnanceiro\Models\PersonAddress', 'person_id');
    }

    public function contacts() {
        return $this->hasMany('Sisnanceiro\Models\PersonContact', 'person_id');
    }

}
