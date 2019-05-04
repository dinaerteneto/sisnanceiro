<?php

namespace Sisnanceiro\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\EventGuest;
use Sisnanceiro\Repositories\EventRepository;
use Sisnanceiro\Services\EventGuestService;
use Sisnanceiro\Services\PersonService;

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
        $data['value_per_person'] = FloatConversor::covert($data['value_per_person']);
        $data['description']      = nl2br($data['description']);
        return $data;
    }

    /**
     * prepare data to save in model
     * @param array $data data to map
     * @return array
     */
    public function mapGuest(array $data)
    {
        $person   = $this->personService->findBy('email', $data['email']);
        $personId = $person ? $person->id : null;
        $event    = $this->repository->find($data['event_id']);

        return [
            'event_id'               => $data['event_id'],
            'person_id'              => $personId,
            'person_name'            => $data['name'],
            'email'                  => $data['email'],
            'token_email'            => Hash::make(Str::random()),
            'status'                 => EventGuest::STATUS_WAITING,
            'invited_by_id'          => isset($data['invited_by_id']) && $data['invited_by_id'] !== null ? $data['invited_by_id'] : null,
            'responsable_of_payment' => isset($data['responsable_of_payment']) && $data['responsable_of_payment'] === 'me' ? $data['invited_by_id'] : null,
            'value'                  => !empty($event->value_per_person) && $event->value_per_person > 0 ? $event->value_per_person : 0,
        ];
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
     * Add Guests on event
     * @param int $eventId id of the event
     * @param array $data
     * @return Boolean
     */
    public function addGuest($eventId, array $data)
    {
        if ($data) {
            foreach ($data as $key => $record) {
                $data[$key]['event_id'] = $eventId;
                $this->eventGuestService->store($this->mapGuest($data[$key]), 'create');
            }
        }
        return true;
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
