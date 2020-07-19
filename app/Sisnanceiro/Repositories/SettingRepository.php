<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Setting;

class SettingRepository extends Repository {

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

}
