<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\UserGrouping;

class UserGroupingRepository extends Repository
{
    public function __construct(UserGrouping $model)
    {
        $this->model = $model;
    }

}
