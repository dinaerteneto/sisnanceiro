<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\User;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

}
