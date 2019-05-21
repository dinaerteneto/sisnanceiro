<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{

    use TenantModels;

    protected $table      = 'user_group';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id', 'user_id', 'name'
    ];

    public function company()
    {
        return $this->hasMany('Sisnanceiro\Models\Company');
    }    

    public function user() 
    {
        return $this->hasMany('Sisnaceiro\Models\User');
    }

}
