<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
	use TenantModels;

	const GROUP_MASTER = 1;
	const GROUP_ADMIN = 2;
	const GROUP_USER = 3;

	protected $fillable = [
		'id', 'company_id', 'email', 'remember_token', 'password',
	];

	protected $hidden = ['password'];

	public function username() {
		return 'login';
	}

	public function company() {
		return $this->hasOne('Sisnanceiro\Models\Company', 'id', 'company_id');
	}

	public function person() {
		return $this->belongsTo('Sisnanceiro\Models\Person', 'id', 'id');
	}

}
