<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\EventRepository;

class EventService extends Service
{
    protected $rules = [
        'create' => [
            'name'           => 'required|max:255',
            'start_date'     => 'required',
            'end_date'       => 'required',
            'value'          => 'float',
            'description'    => 'required',
            'zipcode'        => 'required',
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
            'value'          => 'float',
            'description'    => 'required',
            'zipcode'        => 'required',
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

    public function store(array $data, $rules = false)
    {
        $carbonStartDate    = Carbon::createFromFormat('d/m/Y H:i', $data['start_date'] . ' ' . $data['start_time']);
        $carbonEndDate      = Carbon::createFromFormat('d/m/Y H:i', $data['end_date'] . ' ' . $data['end_time']);
        $data['start_date'] = $carbonStartDate->format('Y-m-d H:i:s');
        $data['end_date']   = $carbonEndDate->format('Y-m-d H:i:s');

        return parent::store($data, $rules);
    }

}
