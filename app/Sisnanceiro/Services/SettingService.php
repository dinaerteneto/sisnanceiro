<?php

namespace Sisnanceiro\Services;

use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\SettingRepository;

class SettingService extends Service
{

    protected $rules = [
        'create' => [
            'company_id' => 'required|int',
            'simple_product' => 'required|bool'
        ],
        'update' => [
            'id' => 'required|int',
            'simple_product' => 'required|bool'
        ],
    ];

    protected $repository;

    public function __construct(
        Validator $validator,
        SettingRepository $settingRepository
    ) {
        $this->validator     = $validator;
        $this->repository    = $settingRepository;
    }

    public function get() {
        $companyId = Auth::user()->company_id;
        return $this->repository->find($companyId);
    }

}
