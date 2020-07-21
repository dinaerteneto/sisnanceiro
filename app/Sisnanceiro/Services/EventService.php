<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Repositories\EventRepository;
use Sisnanceiro\Services\EventGuestService;
use Sisnanceiro\Services\PersonService;

class EventService extends Service
{
    protected $rules = [
        'create' => [
            'name'                   => 'required|max:255',
            'start_date'             => 'required',
            'end_date'               => 'required',
            'people_limit'           => 'int',
            'guest_limit_per_person' => 'int',
            'days_for_cancel'        => 'int',
            'value_per_person'       => 'numeric',
            'phone'                  => 'string',
            'whatsapp'               => 'string',
            'value'                  => 'numeric',
            'description'            => 'string',
            'zipcode'                => 'string',
            'address'                => 'string',
            'address_number'         => 'string',
            'city'                   => 'string',
            'complement',
            'reference',
            'latitude',
            'longitude',
        ],
        'update' => [
            'name'                   => 'required|max:255',
            'start_date'             => 'required',
            'end_date'               => 'required',
            'people_limit'           => 'int',
            'guest_limit_per_person' => 'int',
            'days_for_cancel'        => 'int',
            'value_per_person'       => 'numeric',
            'phone'                  => 'string',
            'whatsapp'               => 'string',
            'value'                  => 'numeric',
            'description'            => 'string',
            'zipcode'                => 'string',
            'address'                => 'string',
            'address_number'         => 'string',
            'city'                   => 'string',
            'complement',
            'reference',
            'latitude',
            'longitude',
        ],
    ];

    protected $eventGuestRepository;
    protected $personService;

    public function __construct(
        Validator $validator,
        EventRepository $repository,
        EventGuestService $eventGuestService,
        PersonService $personService
    ) {
        $this->validator         = $validator;
        $this->repository        = $repository;
        $this->eventGuestService = $eventGuestService;
        $this->personService     = $personService;
    }

    /**
     * prepare data to save in model
     * @param array $data data to map
     * @return array
     */
    public function mapData(array $data)
    {
        $carbonStartDate          = Carbon::createFromFormat('d/m/Y H:i', $data['start_date'] . ' ' . $data['start_time']);
        $carbonEndDate            = Carbon::createFromFormat('d/m/Y H:i', $data['end_date'] . ' ' . $data['end_time']);
        $data['start_date']       = $carbonStartDate->format('Y-m-d H:i:s');
        $data['end_date']         = $carbonEndDate->format('Y-m-d H:i:s');
        $data['value_per_person'] = FloatConversor::convert($data['value_per_person']);
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

    /**
     * Update event data
     * @param Model $model model for persist data
     * @param array $data data for persist
     * @param boolean
     * @return Model
     */
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

    /**
     * return all main guest of the event
     * @param int $eventId
     * @return Collection
     */
    public function findMainGuests($eventId)
    {
        return $this->eventGuestService->allMainGuest($eventId);
    }
}
