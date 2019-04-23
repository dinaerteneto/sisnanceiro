<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\EventRepository;

class EventService extends Service
{
    protected $rules = [
        'create' => [
            'name'           => 'required|max:255',
            'start_date'     => 'required',
            'end_date'       => 'required',
            'value'          => 'required|float',
            'description'    => 'required',
            'zip_code'       => 'required',
            'address'        => 'required',
            'address_number' => 'required',
            'city'           => 'required',
            'complement',
            'reference',
            'latitude',
            'longitude',
        ],
        'update' => [
            'name'           => 'required|max:255',
            'start_date'     => 'required',
            'end_date'       => 'required',
            'value'          => 'required|float',
            'description'    => 'required',
            'zip_code'       => 'required',
            'address'        => 'required',
            'address_number' => 'required',
            'city'           => 'required',
            'complement',
            'reference',
            'latitude',
            'longitude',
        ],
    ];

    public function __construct(Validator $validator, EventRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

}
