<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\User;
use Sisnanceiro\Models\UserGroup;
use Sisnanceiro\Models\UserGrouping;

class UserRepository extends Repository
{
    private $modelUserGroup;
    private $modelUserGrouping;

    public function __construct(User $model, UserGroup $modelUserGroup, UserGrouping $modelUserGrouping)
    {
        $this->model             = $model;
        $this->modelUserGroup    = $modelUserGroup;
        $this->modelUserGrouping = $modelUserGrouping;
    }

    /**
     * Create a new user and create group for this user
     * @param array $data
     * @return boolean
     * @throws \Exception if error in transaction
     */
    public function create(array $data)
    {
        $this->model->create($data);
        $dataUserGroup = [
            'user_id' => $data['id'],
            'name'    => $data['email'],
        ];
        $modelUserGroup = $this->modelUserGroup->create($dataUserGroup);
        $dataUserGrouping = [
            'user_id'       => $data['id'],
            'user_group_id' => $modelUserGroup->id
        ];
        $this->modelUserGrouping->create($dataUserGrouping);
    }

}
