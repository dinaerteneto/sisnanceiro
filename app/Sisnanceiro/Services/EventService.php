<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\EventRepository;
use Illuminate\Database\Eloquent\Model;

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

    public function mapData(array $data)
    {
        $carbonStartDate          = Carbon::createFromFormat('d/m/Y H:i', $data['start_date'] . ' ' . $data['start_time']);
        $carbonEndDate            = Carbon::createFromFormat('d/m/Y H:i', $data['end_date'] . ' ' . $data['end_time']);
        $data['start_date']       = $carbonStartDate->format('Y-m-d H:i:s');
        $data['end_date']         = $carbonEndDate->format('Y-m-d H:i:s');
        $data['value_per_person'] = FloatConversor::covert($data['value_per_person']);
        $data['description']      = nl2br($data['description']);
        return $data;
    }

    /**
     * map data to save on event table
     * @param array $data array to data for save in event table
     * @return boolean
     */
    public function store(array $data, $rules = false)
    {
        $data = $this->mapData($data);
        return parent::store($data, $rules);
    }

    public function update(Model $model, array $data, $rules = false)
    {
        $data = $this->mapData($data);
        return parent::update($model, $data, $rules);
    }

    /**
     * combine initial date and final date to find events
     * @param string $start inicial date to find events
     * @param string $end final date to find event
     * @return Collection
     */
    public function load($start, $end)
    {
        return $this->repository->load($start, $end);
    }

}
